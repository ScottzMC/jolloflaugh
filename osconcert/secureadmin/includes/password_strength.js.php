<script language="javascript" type="text/javascript">
function check_password_strength(pwd)
{
		var r_sym = new RegExp("[~`!@#$%^&*_=|\/><,?;:+-]","i");
		var r_caps =/^[A-Z]+$/; //new RegExp("[A-Z]","i");
		var r_small =/^[a-z]+$/; // new RegExp("[a-z]","i");
            tot_average        = 0.0; 
            pwdav_len            = 0.0;                 
            pwdav_caps        = 0.0;                 
            pwdav_nums        = 0.0;                                         
            pwdav_small        = 0.0; 
            pwdav_puncts        = 0.0;                 
            total_char_used = 0; 
            if (pwd.length>0) 
            { 
                p_limit = 5; 
                pwd_len = pwd.length; 
                nums_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
                    if (!isNaN(val)) 
                        nums_cnt++; 
                } 
                if (nums_cnt>0) 
                    total_char_used += 10; 
					
                small_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res = r_small.test(val);
                    if (res==true)
                        small_cnt++; 
                } 
                if (small_cnt>0) 
                    total_char_used += 26; 

                caps_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res = r_caps.test(val);
                    if (res==true) 
                        caps_cnt++; 
                } 
                if (caps_cnt>0) 
                    total_char_used += 26; 

                puncts_cnt = 0; 
                for(i=0;i<pwd_len;i++) 
                { 
					val=pwd.substr(i,1);
					res=val.match(r_sym);
                    if (res!=null) 
                        puncts_cnt++; 
                } 
                if (puncts_cnt>0) 
                    total_char_used += 31; 

                // calculation   
				len_min=<?php echo ENTRY_PASSWORD_MIN_LENGTH;?>;
				len_max=16;                                      
                if ((pwd_len>len_min) && (pwd_len<len_max)) 
                    pwdav_len += (100 / p_limit); 
                // caps 
                tot_average += pwdav_len; 
                if (20 <= ((caps_cnt * 100) / pwd_len)) 
                    pwdav_caps += (100 / p_limit); 
                else 
                    pwdav_caps += (caps_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_caps; 
                // numbers 
                if (20 <= ((nums_cnt * 100) / pwd_len)) 
                    pwdav_nums += (100 / p_limit); 
                else 
                    pwdav_nums += (nums_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_nums; 
                // small 
                if (30 <= ((small_cnt * 100) / pwd_len)) 
                    pwdav_small += (100 / p_limit); 
                else 
                    pwdav_small += (small_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                tot_average += pwdav_small; 
				
                // symbols 
                if (10 <= ((puncts_cnt * 100) / pwd_len)) 
                    pwdav_puncts += (100 / p_limit); 
                else 
                    pwdav_puncts += (puncts_cnt > 0) ? ((100 / p_limit) - 10) :  0; 
                 
                tot_average += pwdav_puncts;             
                charSet = total_char_used; 
            } 
			if(tot_average<=40)
				return false;
			else
				return true;
	}	
</script>