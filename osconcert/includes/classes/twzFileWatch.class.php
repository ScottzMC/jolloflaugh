<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
/*
    twzFileWatch.class.php                                     v3.1.2 2016-08-23
    
    COPYRIGHT INFORMATION
    All code in this file and associated files is copyright
    ©2008-2016 tweezy.net.au unless otherwise noted.
    ___________________________________________________________
    
    For more information, see twzFileWatch-doc.txt
    Download: http://tweezy.net.au/filewatch.html
    ___________________________________________________________


    Copyright (C) 2016 Tony Phelps
    ---------------------------------------------------------------------
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    ---------------------------------------------------------------------
    email: dev@tweezy.net.au
    post:  P O Box 200 Kingston Tas 7051 AUSTRALIA
    ---------------------------------------------------------------------
*/



class twzFilewatch {

const GENERAL_INDEX = ' -general- ';
const PERMS_UNKNOWN = '???';

protected $optSiteName;
protected $optServerDomain='';

protected $optSaveFile;
protected $optCheckFolder;
protected $optRecurseLevel;
protected $optExcludeFolder=array();
protected $optExcludeFiles=array();
protected $optUseChecksums=false;
protected $optRecurseLevelMd5=1;
protected $optOnDateChange=false;
protected $optMinInterval;
protected $optFollowSymlinks=false;
protected $optSuppressNewList=false;

protected $optEmailTo;
protected $optSendEmail=true;
protected $optReportAlways=false;
protected $optEmailSubject;
protected $optSubjectNoChange;
protected $optEmailBcc='';

protected $optLogStyle='line';
protected $optShow=array(); // ( base, duration, time ) each a boolean or string

protected $LogPathname='';

protected $StartTime;
protected $LastCheckInt=false;
protected $CurrentFile=array();
protected $PreviousFile=array();
protected $Change=array();

protected $TotalFileCount=0;
protected $TotalUpdated=0;
protected $TotalDeleted=0;
protected $TotalAdded=0;
protected $TotalSizeIncrease=0;


public function __construct($SiteName, $CheckFolder, $RecurseLevel, $EmailTo, $FollowSymlinks=false)
    {
    $this->StartTime=microtime(true);

    $this->optSiteName=$SiteName;
    $this->optCheckFolder=$CheckFolder;
    $this->optRecurseLevel=$RecurseLevel;
    $this->optEmailTo=$EmailTo;
    $this->optFollowSymlinks=(bool)$FollowSymlinks;
    
    // default settings..
    $this->emailSubject();
    $this->saveFile();
    $this->minInterval();
    $this->reportBase(true);
    $this->reportDuration(false);
    $this->reportTime(false);
    }

public function reportBase($Show=true)
    { return $this->_report('base', $Show); }
public function reportDuration($Show=true)
    { return $this->_report('duration', $Show); }
public function reportTime($Show=true)
    { return $this->_report('time', $Show);  }

protected function _report($What, $Show)
    {
    // Whether to show a specific item in the email result
    // $What: base|duration|time
    // $Show: boolean or string; if true, uses default string

    $default=array('base'=>'Base directory is', 'duration'=>'Processing time', 'time'=>'Current server time');

    if(isset($default[$What]))
        {
        if(is_string($Show))
            { $this->optShow[$What]=$Show; }
        elseif($Show)
            { $this->optShow[$What]=$default[$What]; }
        else
            { $this->optShow[$What]=false; }

        return true;
        }
    else
        { return false; }
    }

public function emailSubject($EmailSubject='', $SubjectNoChange='')
    {
    // set email subject string
    if(!$EmailSubject)
        { $EmailSubject='filewatch: [SiteName] file change detected'; }
    if(!$SubjectNoChange)
        { $SubjectNoChange='filewatch: [SiteName] no changes detected'; }
    
    $this->optEmailSubject=str_replace('[SiteName]', $this->optSiteName, $EmailSubject);
    $this->optSubjectNoChange=str_replace('[SiteName]', $this->optSiteName, $SubjectNoChange);
    }
    
public function emailBcc($BccEmail)
    { if($this->_emailIsValid($BccEmail, 3)) { $this->optEmailBcc=$BccEmail; } }

public function excludeFolders($ExcludeFolder=array())
    { $this->optExcludeFolder=$ExcludeFolder; }
    
public function excludeFiles($ExcludeFiles=array())
    { $this->optExcludeFiles=$ExcludeFiles; }
    
public function minInterval($MinInterval='')
    {
    if(strtotime('+'.$MinInterval) <= 0) $MinInterval='1 hour';
    $this->optMinInterval=$MinInterval;
    }
    
public function serverDomain($ServerDomain='')
    { $this->optServerDomain=$ServerDomain; }
    
public function saveFile($SaveFile='')
    {
    if(''==$SaveFile) $SaveFile='./twzFileWatch.txt';
    $this->optSaveFile=$SaveFile;
    }
    
public function doSendEmail($SendEmail=true)
    { $this->optSendEmail=(bool)$SendEmail; }
    
public function reportAlways($ReportAlways=true)
    { $this->optReportAlways=(bool)$ReportAlways; }

public function logFile($Basename='', $AppendSpec='_Y-m', $LogStyle='line')
    {
    $Basename=(string)$Basename;
    $AppendSpec=(string)$AppendSpec;
    $this->optLogStyle=(string)$LogStyle;

    if($Basename)
        {
        $this->LogPathname = $Basename.date($AppendSpec).'.log';
        if(!$this->_isWritable($this->LogPathname))
            { die('twzFW: logFile '.$this->LogPathname.' is not writable'); } // if die() is removed, set LogPathname to empty string (no logging)
        }
    }

public function suppressNewList($SuppressNewList=true)
    { $this->optSuppressNewList=(bool)$SuppressNewList; }
    
public function useChecksums($UseChecksums=false, $RecurseLevelMd5=1, $OnDateChange=false)
    {
    $this->optUseChecksums=(bool)$UseChecksums;
    $this->optRecurseLevelMd5=(int)$RecurseLevelMd5;
    $this->optOnDateChange=(bool)$OnDateChange;
    }

public function checkFiles()
    {
    // Public method to compare current files with previously saved file list, and report the changes.

    if(isset($_GET['close']) and 'yes'==$_GET['close'])
        { $this->_disconnectClient(); } // for twzFileWatch-multi.php
    
    $ResultMessage=$this->_rebuildSavefile();

    // Send or show result..
    if($ResultMessage<>'')
        {
        // send email or echo the results..

        // Get server name..
        if($this->optServerDomain)
            { $ServerName=$this->optServerDomain; }
        elseif(isset($_SERVER['SERVER_NAME']) and $_SERVER['SERVER_NAME'])
            { $ServerName=$_SERVER['SERVER_NAME']; }
        else
            { $ServerName=@exec('hostname'); }
        if(!$ServerName) { $ServerName='unknown'; } // added 10/8/16

        // Get App Name..
        $AppName=basename($this->_getParentScriptPathname(), '.php');
		
        if(!$AppName) { $AppName='osconcert'; } // just in case
		$AppName='osconcert';
        $EmailFrom=$AppName.'@'.$ServerName;

        // Show processing time..
        if($this->optShow['duration'])
            { $ResultMessage.="\r\n".$this->optShow['duration'].' '.round(microtime(true) - $this->StartTime, 3).' seconds'; }

        // Send or echo results..
        if($this->optSendEmail)
            {
            $EmailHeaders=array('From'=>"$AppName File Watch <$EmailFrom>", 'Cc'=>'', 'Bcc'=>$this->optEmailBcc, 'Reply-To'=>'');
            $SendResult=$this->_emailSend($this->optEmailTo, $this->optEmailSubject, $ResultMessage, $EmailHeaders);
            if(!$SendResult) { die('twzFW: Failed to send email'); }
            }
        else
            { echo "".'<pre>'.$ResultMessage.'</pre>'; }
        }
    }

public function getFilesInfo($AsArray=false, $Refresh=false)
    {
    // Returns the current saved file list, either as a folder-indexed array, or raw text from save-file.
    // If $Refresh is true, rebuilds the savefile with current files first, otherwise returns the file list previously saved.
    // NOTE: If $Refresh is true this will NOT notify you of any file changes!!

    // THIS METHOD IS EXPERIMENTAL AND UNDOCUMENTED

    if($Refresh)
        { $this->_rebuildSavefile(false); }

    if($AsArray)
        { return $this->_getSavedInfo(); }
    elseif(is_file($this->optSaveFile))
        { return file_get_contents($this->optSaveFile); }
    else
        { return ''; }
    }

protected function _checkVars()
    {
    if(is_string($this->optCheckFolder)) { $this->optCheckFolder=array($this->optCheckFolder); } // optCheckFolder must be an array
    
    // bad email is a FATAL ERROR..
    if($this->optSendEmail and !$this->_emailIsValid($this->optEmailTo, 3))
        { die('twzFW: EmailTo is not valid'); }
    }
    
protected function _getSavedInfo()
    {
    // Returns an array of files info from previous savefile (empty array if none)
    
    if(is_file($this->optSaveFile))
        {
        // get date of last check..
        $this->LastCheckInt=filemtime($this->optSaveFile);
        if(time() < strtotime(date('j M Y g:i:s a', $this->LastCheckInt).' +'.$this->optMinInterval))
            { die; } // ABORT
        
        // read previous file list..
        $PreviousFileInfo=file($this->optSaveFile);
        }
    else
        {
        $this->LastCheckInt=false;
        $PreviousFileInfo=array(); // no previous
        $this->Change[self::GENERAL_INDEX][]='INITIAL RUN - everything is new!';
        }

    // Convert $PreviousFileInfo to internal format..
    $SavedFiles=array();
    foreach($PreviousFileInfo as $ThisInfo)
        {
        $PrevBits=explode("\t", ($ThisInfo)); // was trim($ThisInfo) but this removes hanging tab char! 26/8/08 [actually this problem might have disappeared 27/8/08]

        if(count($PrevBits)>3)
            {
            $FolderName=$PrevBits[0];
            $FileName=$PrevBits[1];
            $FilePerm=(count($PrevBits)>4) ? trim($PrevBits[4]) : self::PERMS_UNKNOWN;

            $SavedFiles[$FolderName][$FileName]['date']=$PrevBits[2];
            $SavedFiles[$FolderName][$FileName]['size']=trim($PrevBits[3]);
            $SavedFiles[$FolderName][$FileName]['perm']=trim($FilePerm);
            
            if(count($PrevBits)>5)
                { $SavedFiles[$FolderName][$FileName]['md5']=trim($PrevBits[5]); }
            }
        }
    return $SavedFiles;
    }
    
protected function _getCurrentInfo()
    {
    // Returns an array of current files info from the filesystem
    
    $CurrentFiles=array();
    if($this->optUseChecksums)
        {
        $ListFormat='all'; // get size, date, perms PLUS md5_file hash
        $ListRecursion=$this->optRecurseLevel . ',' . $this->optRecurseLevelMd5;
        }
    else
        {
        $ListFormat='full'; // only size, date, perms
        $ListRecursion=$this->optRecurseLevel;
        }
       
    foreach($this->optCheckFolder as $ThisFolder)
        {
        if(''==$ThisFolder) $ThisFolder='./';
        if(substr($ThisFolder,-1)!='/') { $ThisFolder.='/'; } // append slash
        if(!is_dir($ThisFolder))
            {
            $this->Change[$ThisFolder][]='No such directory';
            break;
            }

        // Get current file info..
        $CurrentFiles=array_merge($CurrentFiles, $this->_fileList($ThisFolder, '', $ListFormat, 
                $ListRecursion, $this->optExcludeFolder, $this->optFollowSymlinks));

        if(count($CurrentFiles[$ThisFolder])==0)
            { $this->Change[$ThisFolder][]='No files found'; }
        }
    return $CurrentFiles;
    }
    
protected function _checkChanges()
    {
    // Check all previous files to see if changed or deleted..
    // Sets $this->TotalSizeIncrease, $this->TotalUpdated, $this->TotalDeleted
    // and updates $this->Change commentary
    //
    // see also: _checkAdditions()
    
    foreach($this->PreviousFile as $ThisFolder=>$ThisFileInfo)
        {
        foreach($ThisFileInfo as $ThisFilename=>$ThisSizeDate)
            {
            $PreviousSize = $ThisSizeDate['size'];

            if(isset($this->CurrentFile[$ThisFolder][$ThisFilename]))
                {
                $NewSize=$this->CurrentFile[$ThisFolder][$ThisFilename]['size'];

                $PreviousPerm=$ThisSizeDate['perm'];
                $NewPerm=$this->CurrentFile[$ThisFolder][$ThisFilename]['perm'];
                $PermChanged=($NewPerm<>$PreviousPerm and $PreviousPerm<>self::PERMS_UNKNOWN);

                $DateSizeChanged=($this->CurrentFile[$ThisFolder][$ThisFilename]['date'] <> $ThisSizeDate['date'] or $NewSize <> $PreviousSize);

                // only look at md5-hash if 
                //      (a) neither size nor date has changed, or
                //      (b) $this->optOnDateChange and size has not changed..
                if(!$DateSizeChanged or ($this->optOnDateChange and $NewSize == $PreviousSize))
                    {
                    $NewChecksum=(isset($ThisSizeDate['md5'])) ? $ThisSizeDate['md5'] : false;
                    $OldChecksum=(isset($this->CurrentFile[$ThisFolder][$ThisFilename]['md5'])) ? $this->CurrentFile[$ThisFolder][$ThisFilename]['md5'] : false;
                    // only report checksum if present both before and now, and they are different..
                    if($NewChecksum and $OldChecksum and $NewChecksum != $OldChecksum)
                        {
                        $this->Change[$ThisFolder][]="CHECKSUM: $ThisFilename (changed from $OldChecksum to $NewChecksum)";
                        }
                    }
                
                if($DateSizeChanged or $PermChanged)
                    {
                    $this->TotalUpdated++;
                    if($DateSizeChanged)
                        {
                        $this->Change[$ThisFolder][]="CHANGED: $ThisFilename (FROM ".$ThisSizeDate['date'].' SIZE='.
                            $PreviousSize." TO ".$this->CurrentFile[$ThisFolder][$ThisFilename]['date'].' SIZE='.
                            $NewSize.')';
                        }
                    if($PermChanged)
                        {
                        $this->Change[$ThisFolder][]="  PERMS: $ThisFilename (changed from $PreviousPerm to $NewPerm)";
                        }
                    }
                }
            else
                {
                $NewSize=0;
                $this->TotalDeleted++;
                $this->Change[$ThisFolder][]="DELETED: $ThisFilename (was ".
                        $ThisSizeDate['date']." SIZE=".$ThisSizeDate['size'].")";
                }

            $this->TotalSizeIncrease += $NewSize-$PreviousSize;
            }
        }
    }
    
protected function _checkAdditions()
    {
    // Check each current file to see if it was there before.
    // New files are added to $this->Change[foldername] array
    // Returns text for ALL current files to save in file list
    //
    // see also: _checkChanges()
    
    $SaveCurrent=''; $this->TotalFileCount=0;
    foreach($this->CurrentFile as $ThisFolder=>$ThisFileInfo)
        {
        $this->TotalFileCount += count($ThisFileInfo);
        foreach($ThisFileInfo as $ThisFilename=>$ThisSizeDate)
            {
            if(is_file($ThisFolder.$ThisFilename)  // isfile NEW 26/8/08 (not necessary!?)
                and $ThisFilename <> basename($this->optSaveFile) // don't include SaveFile (or any file with the same name!)
                and $ThisFilename <> basename($this->LogPathname) // don't include current LogFile (or any file with the same name!)
                and !in_array($ThisFilename, $this->optExcludeFiles)
                and !in_array($ThisFolder.$ThisFilename, $this->optExcludeFiles))
                {
                if(!isset($this->PreviousFile[$ThisFolder][$ThisFilename]))
                    {
                    $this->TotalAdded++;
                    $this->TotalSizeIncrease += $ThisSizeDate['size'];
                    $this->Change[$ThisFolder][]="  ADDED: $ThisFilename (".$ThisSizeDate['date']." SIZE=".$ThisSizeDate['size'].")";
                    }
                $SaveCurrent.=$ThisFolder."\t".$ThisFilename."\t".$ThisSizeDate['date']."\t".$ThisSizeDate['size']."\t".$ThisSizeDate['perm'];
                if(isset($ThisSizeDate['md5'])) { $SaveCurrent.="\t".$ThisSizeDate['md5']; }
                $SaveCurrent.="\r\n";
                }
            }
        }
    
    return $SaveCurrent;
    }
    
protected function _getResultMessage()
    {
    // Work out what to say..
    $ResultMessage='Watching '.$this->TotalFileCount.' files in '.count($this->CurrentFile).' directories';
    //$ResultMessage.=' (recursion level '.$this->optRecurseLevel;
    if($this->optUseChecksums)
        {
       // $ResultMessage.=' with checksum';
        if($this->optRecurseLevelMd5 < $this->optRecurseLevel); //$ResultMessage.=' to level '.$this->optRecurseLevelMd5;
        }
   // $ResultMessage.=')';

    // Show 'Base directory' (only if one or more optCheckFolder is relative) [NEW 16/8/2016]
    if($this->optShow['base'])
        {
        $RelFolder=false;
        foreach($this->optCheckFolder as $ThisFolder)
            { if($ThisFolder{0}!='/') { $RelFolder=true; break;} } // relative if doesn't start with slash
        if($RelFolder)
            {
            $ThisScript=$this->_getParentScriptPathname();
            if($ThisScript)
                { $ResultMessage.="\r\n".$this->optShow['base'].' '.dirname($ThisScript); } // realpath() not required?
            }
        unset($RelFolder, $ThisScript);
        }

    // Show current server time..  [NEW 18/8/2016]
    if($this->optShow['time'])
        {
        $ResultMessage.="\r\n".$this->optShow['time'].' '.date('r'); // eg Thu, 18 Aug 2016 16:01:07 +1100
        $Timezone='';
        if(function_exists('date_default_timezone_get')) // same as date('e'), but both only since PHP 5.1
            { $Timezone=date_default_timezone_get(); } // eg UTC, Australia/Hobart
        $Timezone.=' '.date('T'); // eg AEST, AEDT, EST, MDT
        $ResultMessage.=' ('.trim($Timezone).')';
        unset($Timezone);
        }

    // See what changes we found..
    $SayLastCheck=($this->LastCheckInt) ? date('Y-m-d H:i:s', $this->LastCheckInt) : '(never)';
    $FoldersAffected=0;
	
    if(count($this->Change)>0)
        {
        if($this->optSuppressNewList and 0==count($this->PreviousFile))
            { $ResultMessage.="\r\n\r\nFull file list is suppressed."; }
        else
            {
			
            $ResultMessage.="\r\n\r\nThe following file changes since $SayLastCheck were detected:";
            foreach($this->Change as $ChFolder=>$ChList)
                {
                $FoldersAffected++;
				//let's hide the real admin name
				$configuration_query = tep_db_query("select * from configuration where configuration_key='LOG_PATH'");
				while ($configuration = tep_db_fetch_array($configuration_query)) 
				{
				if($ChFolder=='./'.$configuration['configuration_value'].'/')
				{
					$ChFolder='./administration/';
				}
				}
				//eof
                $ResultMessage.="\r\n\r\nChanges in directory $ChFolder \r\n";
                $ResultMessage.=implode("\r\n", $ChList);
                }

            $SayFilesizedelta='Net file size ';
            $SayFilesizedelta.=($this->TotalSizeIncrease>=0) ? 'increase ' : 'decrease ';
            $SayFilesizedelta.=$this->_formatFileSize(abs($this->TotalSizeIncrease));

            $ResultMessage.="\r\n\r\n".($this->TotalAdded + $this->TotalDeleted + $this->TotalUpdated)
                .' files affected ('
                .$this->TotalAdded.' added, '.$this->TotalDeleted.' deleted, '.$this->TotalUpdated." updated) \r\n"
                .' in '.$FoldersAffected." directories \r\n"
                .$SayFilesizedelta;
            }
        }
    elseif($this->optReportAlways)
        {
        $ResultMessage.="\r\n\r\nNo file changes were detected since $SayLastCheck in ";
        if(count($this->optCheckFolder)>1)
            { $ResultMessage.="these directories:\r\n\r\n ".implode("\r\n ", $this->optCheckFolder)."\r\n"; }
        else
            { $ResultMessage.='directory '.$this->optCheckFolder[0]."\r\n"; }

        $this->optEmailSubject=$this->optSubjectNoChange;
        }
    return $ResultMessage;
    }

protected function _disconnectClient()
    {
    // Close the browser connection but continue running..
    // (feature added 28/3/11 for twzFileWatch-multi.php)
    // code from http://stackoverflow.com/questions/124462/asynchronous-php-calls (Christian Davén 13/2/10)

    while(ob_get_level()) { ob_end_clean(); }
    header('Connection: close');
    ignore_user_abort();
    ob_start();
    echo 'Connection Closed';
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();
    }

protected function _rebuildSavefile($ReturnMessage=true)
    {
    // Writes current file list to the savefile.
    // If the savefile is not writable, returns an error message. Otherwise,
    //      If $ReturnMessage is true, returns the text to be included in email to owner, or empty string if no email is to be sent.
    //      If $ReturnMessage is false, returns an empty string

    $this->_checkVars();
    
    if($this->_isWritable($this->optSaveFile))
        {
        // get previously saved info..
        $this->PreviousFile = $this->_getSavedInfo();
        
        // get current file info..
        $this->CurrentFile = $this->_getCurrentInfo();

        // Check all previous files to see if changed or deleted..
        $this->_checkChanges();
        // ..sets TotalSizeIncrease, TotalUpdated, TotalDeleted

        // Check each current file to see if it was there before..
        $SaveCurrent = $this->_checkAdditions();
        // ..sets TotalSizeIncrease, TotalAdded, TotalFileCount
        //   returns text to save in file list
        
        //Always write save-file, to ensure we set filemtime.. (could use touch?)
        if($SaveCurrent<>'')
            {
            $SavedOk=$this->_fileWrite($this->optSaveFile, $SaveCurrent, true);
            if($SavedOk!==true) { $this->Change[self::GENERAL_INDEX][]=''; $this->Change[self::GENERAL_INDEX][]='ERROR - unable to write to '.$this->optSaveFile; }
            }

        $ResultMessage='';
        if($ReturnMessage and (count($this->Change)>0 or $this->optReportAlways))
            { $ResultMessage=$this->_getResultMessage(); }
        
        }
    else
        { $ResultMessage='Save file '.$this->optSaveFile.' is not writable!'; }

    if($ResultMessage and $this->LogPathname)
        {
        // write result to log file; either full result or a single line..
        $LogText=('full'==$this->optLogStyle)
            ? $this->optEmailSubject."\r\n-------------------\r\n".$ResultMessage." \r\n-------------------\r\n\r\n"
            : $this->optEmailSubject."\t".'Watching '.$this->TotalFileCount.' files; '.$this->TotalAdded.' added, '.$this->TotalDeleted.' deleted, '.$this->TotalUpdated.' updated';

        $LogText=date('Y-m-d H:i:s')."\t".$LogText."\r\n";
        $WriteOk=$this->_fileWrite($this->LogPathname, $LogText);
        if(is_string($WriteOk)) { die('twzFW: '.$WriteOk); }
        }

    return $ResultMessage;
    }

protected function _emailSend($EmailTo, $EmailSubject, $EmailBody, $EmailHeaders)
    {
    // Sends an email. Returns true on success or false on error
    // $EmailHeaders will be an array with indexes 'From', 'Cc', 'Bcc' and 'Reply-To'

    $headers="MIME-Version: 1.0\r\n"
        ."Content-type: text/plain; charset=utf-8\r\n"
        ."From: ".$EmailHeaders['From']."\n";

    if($EmailHeaders['Cc']<>'') { $headers .= 'Cc: '.$EmailHeaders['Cc']."\r\n"; }
    if($EmailHeaders['Bcc']<>'') { $headers .= 'Bcc: '.$EmailHeaders['Bcc']."\r\n"; }
    if($EmailHeaders['Reply-To']<>'') { $headers .= 'Reply-To: '.$EmailHeaders['Reply-To']."\r\n"; }
    
    return mail($EmailTo, $EmailSubject, $EmailBody, $headers); // true on success, false on error
    }

    
// from twzInc library..
// ------------------------------------------------------------------------
protected function _emailIsValid($EmailAddr,$Max=1) { $EmailRegex='/^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i'; $Email=explode(',',$EmailAddr); if(count($Email)>$Max) return false; foreach($Email as $ThisEmail) { if(!preg_match($EmailRegex, $ThisEmail)) return false; } return true; }
protected function _isWritable($Pathname) { if (is_file($Pathname) and is_writable($Pathname)) { return true; } elseif(!is_file($Pathname) and is_writable(dirname($Pathname))) { if($handle=@fopen($Pathname, 'w')) {  fclose($handle); unlink($Pathname); return true; } } else { return false; } }
// _fileWrite is twzInc FileWrite2 (returns bTrue or error string)
protected function _fileWrite($Pathname, $TheText='', $Replace=false) { $FileMode=($Replace) ? 'w' : 'a'; if ((is_file($Pathname) and is_writable($Pathname)) or (!is_file($Pathname) and is_writable(dirname($Pathname)))) { if (!$handle = @fopen($Pathname, $FileMode)) { return "Cannot open file $Pathname."; } if (!fwrite($handle, $TheText)) { fclose($handle); return "Error writing to file $Pathname."; } fclose($handle); return true; } else { return "File $Pathname is not writable."; } }
protected function _formatFileSize($bytes) { if(!is_numeric($bytes)) return $bytes; if ($bytes<1000) return ($bytes.' bytes'); elseif ($bytes<1000000) return (number_format($bytes/1024, 1).' KB'); elseif ($bytes<1000000000) return (number_format($bytes/1024/1024, 1).' MB'); else return number_format($bytes/1024/1024/1024, 1).' GB'; }
//_fileList is based on twzInc FileListRX (global function), but tweaked for use as class method
protected function _fileList($FolderName, $RegexPattern='', $Format='', $RecurseLevel=0, $ExcludeFolder=array(), $FollowSymlinks=false, $ThisLevel=0) { if(substr($FolderName,-1)<>'/') $FolderName.='/'; if($ThisLevel<=0) $ThisLevel=0; if(''==$RegexPattern) $RegexPattern='/.*/'; $FileList=array(); if(is_numeric($RecurseLevel)){ $RecurseMain=(int)$RecurseLevel; $RecurseMd5=(int)$RecurseLevel; } else { list($RecurseMain, $RecurseMd5) = explode(',', $RecurseLevel); $RecurseMain=(int)$RecurseMain; $RecurseMd5=(int)$RecurseMd5; } if (@$handle = opendir($FolderName)) { foreach($ExcludeFolder as $idx=>$TheFolder) { if(substr($TheFolder, -1) == '/') { $ExcludeFolder[$idx]=substr($TheFolder, 0, -1); } } while (false !== ($Filename=readdir($handle))) { if ($Filename != "." and $Filename != "..") { if(is_dir($FolderName.$Filename) and $ThisLevel<$RecurseMain and ($FollowSymlinks or !is_link($FolderName.$Filename)) and !in_array($FolderName.$Filename, $ExcludeFolder) ) { $UseFormat=('all'==$Format and $ThisLevel>$RecurseMd5) ? 'full' : $Format; $FileList=array_merge( $FileList, $this->_fileList(($FolderName.$Filename), $RegexPattern, $UseFormat, $RecurseLevel, $ExcludeFolder, $FollowSymlinks, ($ThisLevel+1)) ); } elseif(is_file($FolderName.$Filename) and preg_match($RegexPattern, $Filename)) { if('all'==$Format or 'full'==$Format) { $FileDate=date('Y-m-d H:i:s',filemtime($FolderName.$Filename)); $FileSize=filesize($FolderName.$Filename); $FilePerm=substr(decoct(fileperms($FolderName.$Filename)), -3); } if('all'==$Format or 'md5'==$Format) { $FileMd5=md5_file($FolderName.$Filename); } switch($Format) { case 'all': $FileList[$FolderName][$Filename]['md5']=$FileMd5; case 'full': $FileList[$FolderName][$Filename]['size']=$FileSize; $FileList[$FolderName][$Filename]['date']=$FileDate; $FileList[$FolderName][$Filename]['perm']=$FilePerm; break; case 'md5': $FileList[$FolderName][$Filename]=$FileMd5; break; case 'simple': default: $FileList[]=$FolderName.$Filename; } } } } closedir($handle); } return $FileList; }
protected function _getParentScriptPathname() { if(isset($_SERVER['SCRIPT_FILENAME']) and $_SERVER['SCRIPT_FILENAME']) { return $_SERVER['SCRIPT_FILENAME']; } else { $backtrace = debug_backtrace( defined('DEBUG_BACKTRACE_IGNORE_ARGS') ? DEBUG_BACKTRACE_IGNORE_ARGS : false); $parent = array_pop($backtrace); return $parent['file']; } }

} // end class twzFilewatch

?>