<?php
define( '_FEXEC', 1 );
?>
<p><strong>E-TICKET</strong><br />
  Quick Links&gt;TICKET&gt;Events Customer Ticket</p>
<p>Here you can edit the printable PDF E-Ticket details.</p>
<p>Editing is limited.</p>
 <h4>Here is a quick guide.</h4>
<p><strong>Background Image:</strong></p>
<p>For unique ticket background images upload in Admin>Products>Category>Edit>Ticket Image.<br>
<strong>PLEASE NOTE:</strong> Upload width is restricted when using the Ticket Image Upload. Change the restriction in Admin>Shop Settings>Images>Image Upload Width.<br />
</p>
<p>For the best quality, upload hi res images via your FTP account to the /images/ directory</p>
<p>The default ticket size is 180 x 70. Good for users to print four tickets on an A4 size sheet.<br />
<p>Recommended image size is 850px x 330px but you can make adjustments in Admin>Shop settings>Advanced>eTicket Settings</p>
  Any resolution will fit the background of the image but if you are concerned about the time it takes to render the image then try keep your image to a maximum width of 600 pixels.</p>
<h2><strong>Test E-tickets in Admin&gt;Orders&gt;Edit&gt;Ticket</strong></h2>
<p>PLEASE NOTE:<br />
      <strong>Top</strong> Line is the Bigger Arial/DejaVu Font<br />
        <strong>Middle</strong> - Anything in between is mid-sized  font. Thats all we have control of unless you take time to edit the hard coded files in the application.<br />
        <strong>Bottom</strong> Line is the smallest font (Event Condition)<br />
</p>
<p>For the placeholders choose from the content pane</p>
<p>GUIDE TO PLACEHOLDERS:<br />
  %%Concert Name%% = Concert Heading Titles<br />
  %%Concert Venue%% = Venue<br />
  %%Concert Date%% = Date<br />
  %%Concert Time%% = Time<br />
  </p>
<p>%%Discount Type%% = Type of discount added<br />
  %%Coupon%% = If Coupon Discount used or not<br /> 
%%Concert Price%% = Price of ticket as number</p>
<p>%%Customers Name%% = taken from the actual order.<br />
</p>
<p>%%Products Name%% = Seat Name and Number<br />
  %%Ref ID%% = reference ID<br />
  %%Prd ID%% = product ID </p>
 <p>%%Unique Number%% = Complete Unique Number per ticket/barcode e.g 00002000019001335871</p>
<p>%%Payment%% = Payment Method - Free Checkout read Free Event</p>
<p>%%Billing Name%% = Useful if you are mostly Box Office sending to Billing Address Customers.</p>
<p>%%GA ID%% = Should be here if you intend to itemize GA Tickets 1, 2, 3, 4, etc per order.</p>
<p>SPACING (Spacer Bar Clicks): %%SPACE 5%% %%SPACE 10%% %%SPACE 15%% %%SPACE 20%% %%SPACE 25%% </p>
<p>A printing guide should be given at the website.<br />
  Customers should set their printing options to allow 'page handling'<br />
  which will enable multiple tickets per page to be printed at once.</p>
<p>For customer conveniance PDFeTickets when generatedarestored on the server. From time to time you will need to clear out the <strong>PDF Event Tickets</strong> and <strong>Ticket Bar PNG</strong> as they are generated in the /images/tickets folder and may use unnecessary  webspace over time.<br />
  eg: <em>ticket_bar_3-1-368.png</em><br />
  eg: <em>events_tickets__1242527996.pdf</em><br />
  To delete all generated eTickets ...simply set Admin&gt;Shop Settings&gt;Advanced&gt;osConcert Settings&gt;Disable E-Tickets and visit the Front End. When the files are cleared re-enable eTickets=true.</p>
<p>If you don't want the option for customers to view and print PDF E-Tickets. Disable the option in Admin&gt;Shop Settings&gt;Advanced&gt;osConcert Settings&gt;Disable E-Tickets</p>

