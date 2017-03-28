<?php
global $basefile;
global $load;
global $dclass;

?>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <!-- BEGIN SIDEBAR -->
  <div class="page-sidebar nav-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        
			
            
			<ul class="page-sidebar-menu">
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li>
					
				</li>
                
              
                
				<li class="start <?php if($basefile == 'home.php'){ echo 'active';} ?>">
					<a href="home.php">
					<i class="icon-home"></i> 
					<span class="title">Dashboard</span>
					<?php if($basefile == 'home.php'){ echo '<span class="selected"></span>';} ?>
                 	</a>
				</li>
                
              
				
               <!-- user -->	
               
               
               
               <li <?php if($basefile == 'user.php'){ echo 'class="active"' ; } ?>>
					<a href="javascript:;">
					<i class="icon-user"></i> 
					<span class="title">Users</span>
					<?php if($basefile == 'user.php' ){ echo '<span class="selected"></span>';} ?>
                    <span class="arrow "></span>
					</a>
					<ul class="sub-menu">
                      
						<li <?php if( $basefile == 'user.php' && $_REQUEST['type'] == 'company' ){ echo 'class="active"' ; } ?> >
							<a href="user.php?type=company" title="Manage System User">
							<i class="icon-user"></i> 
                            Company Users</a>
						</li>
                       	<li <?php if($basefile == 'user.php' && $_REQUEST['type'] == 'manager'  ){ echo 'class="active"' ; } ?> >
							<a href="user.php?type=manager" title="Manage Managers or End-users">
							<i class="icon-user"></i> 
                            Managers</a>
						</li>
                        <li <?php if($basefile == 'user.php' && $_REQUEST['type'] == 'employee'  ){ echo 'class="active"' ; } ?> >
							<a href="user.php?type=employee" title="Manage Member or End-users">
							<i class="icon-user"></i> 
                            Employee</a>
						</li>
					</ul>
				</li>
               
               <li <?php if($basefile == 'pages.php'){ echo 'class="active"' ; } ?>>
					<a href="pages.php" title="Manage Pages">
					<i class="icon-file-text"></i> 
					<span class="title">Cms</span>
                    <?php if($basefile == 'pages.php' ){ echo '<span class="selected"></span>';} ?>
					</a>
				</li>
                <li <?php if($basefile == 'section.php'){ echo 'class="active"' ; } ?>>
                    <a href="section.php" title="Manage Section">
                        <i class="icon-cog"></i> 
                        <span class="title">Section Setting</span>
                        <?php if($basefile == 'section.php' ){ echo '<span class="selected"></span>';} ?>
                    </a>
                </li>                
                <li <?php if($basefile == 'banner.php'){ echo 'class="active"' ; } ?>>
                    <a href="banner.php" title="Manage Banner">
                        <i class="icon-play-circle"></i> 
                        <span class="title">Banner</span>
                        <?php if($basefile == 'banner.php' ){ echo '<span class="selected"></span>';} ?>
                    </a>
                </li>
                
                <li <?php if($basefile == 'testimonial.php'){ echo 'class="active"' ; } ?>>
                    <a href="testimonial.php" title="Manage Testimonial">
                        <i class="icon-picture"></i> 
                        <span class="title">Testimonial</span>
                        <?php if($basefile == 'testimonal.php' ){ echo '<span class="selected"></span>';} ?>
                    </a>
                </li>
                
                <li <?php if($basefile == 'words_on_street.php'){ echo 'class="active"' ; } ?>>
                    <a href="words_on_street.php" title="Manage Words On Street">
                        <i class="icon-map-marker"></i> 
                        <span class="title">Words On Street</span>
                        <?php if($basefile == 'words_on_street.php' ){ echo '<span class="selected"></span>';} ?>
                    </a>
                </li>
                
                <li <?php if($basefile == 'feature.php'){ echo 'class="active"' ; } ?>>
                    <a href="feature.php" title="Manage Feature">
                        <i class="icon-magnet"></i> 
                        <span class="title">Feature</span>
                        <?php if($basefile == 'feature.php' ){ echo '<span class="selected"></span>';} ?>
                    </a>
                </li>
                
                <li <?php if($basefile == 'faq.php'){ echo 'class="active"' ; } ?>>
                    <a href="faq.php" title="Manage Faq">
                        <i class="icon-question-sign"></i> 
                        <span class="title">Faq</span>
                        <?php if($basefile == 'faq.php' ){ echo '<span class="selected"></span>';} ?>
                    </a>
                </li>
                
                <li <?php if($basefile == 'email_template.php'){ echo 'class="active"' ; } ?>>
                    <a href="email_template.php" title="Manage Email Template">
                        <i class="icon-mail-reply"></i> 
                        <span class="title">Email template</span>
                        <?php if($basefile == 'email_template.php' ){ echo '<span class="selected"></span>';} ?>
                    </a>
                </li>
                
                 <li <?php if($basefile == 'subscription.php'){ echo 'class="active"' ; } ?>>
					<a href="subscription.php" title="Manage Plans">
					<i class="icon-info"></i> 
					<span class="title">Subscription plan</span>
                    <?php if($basefile == 'subscription.php' ){ echo '<span class="selected"></span>';} ?>
					</a>
				</li>
                <li <?php if($basefile == 'permission.php' || $basefile == 'holidays.php' || $basefile == 'timing.php' || $basefile == 'video.php' || $basefile == 'dateformat.php' ){ echo 'class="active"' ; } ?>>
					<a href="javascript:;">
					<i class="icon-cog"></i> 
					<span class="title">Setting</span>
					<?php if($basefile == 'permission.php' || $basefile == 'holidays.php' || $basefile == 'timing.php' || $basefile == 'setting.php' || $basefile == 'video.php' || $basefile == 'dateformat.php'){ echo '<span class="selected"></span>'; $open = 'open';}else{ $open = ''; } ?>
                    <span class="arrow <?php echo $open; ?>"></span>
					</a>
					<ul class="sub-menu">
                    	<li <?php if( $basefile == 'permission.php' ){ echo 'class="active"' ; } ?> >
							<a href="permission.php" title="Manage User Permission">
							<i class="icon-stackexchange"></i> 
                            Permission</a>
						</li>
                        <li <?php if( $basefile == 'holidays.php' ){ echo 'class="active"' ; } ?> >
							<a href="holidays.php" title="Manage Holidays">
							<i class="icon-smile"></i> 
                            Holidays</a>
						</li>
						<li <?php if( $basefile == 'timing.php' ){ echo 'class="active"' ; } ?> >
							<a href="timing.php" title="Manage Working day and time">
							<i class="icon-time"></i> 
                            Working Day & Time</a>
						</li>
                        <li <?php if( $basefile == 'video.php' ){ echo 'class="active"' ; } ?> >
                            <a href="video.php" title="Manage Welcome video">
                            <i class="icon-facetime-video"></i> 
                            Welcome Video</a>
                        </li>
                        <li <?php if( $basefile == 'dateformat.php' ){ echo 'class="active"' ; } ?> >
                            <a href="dateformat.php" title="Date Format">
                            <i class="icon-time"></i> 
                            Date Format</a>
                        </li>
                        <li <?php if( $basefile == 'setting.php' ){ echo 'class="active"' ; } ?> >
                            <a href="setting.php" title="General Setting">
                            <i class="icon-inbox"></i> 
                            General Setting</a>
                        </li>
                    </ul>
				</li>
                
            	
			</ul>
            
			
			<!-- END SIDEBAR MENU -->
		</div>
  <!-- END SIDEBAR -->