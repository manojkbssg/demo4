

<div class="memberProfile">
    <div class="profileContainer info-container" style="width: 27%; margin-bottom: 2%; float:left;">
        <div class="info-border">
        <div>
            <h3>
                <?php 
                $fields = $this->fieldValueLoop($this->subject(), $this->fieldStructure, TRUE);
                if(isset($fields['Profession']) && !empty($fields['Profession'])){
                    echo ucfirst($fields['Profession']);
                } else {
                    echo '&nbsp;';
                }
                ?>
            </h3>
        <div id='profile_photo'>
            <?php echo $this->itemPhoto($this->subject()) ?>
        </div>
        <div class='profilelinks'>
        <?php // This is rendered by application/modules/core/views/scripts/_navIcons.tpl
        echo $this->navigation()
        ->menu()
        ->setContainer($this->userProfileNav)
        ->setPartial(array('_navIcons.tpl', 'core'))
        ->render();  
        ?>
        
        <?php if( count($this->quickAlbumNavigation) > 0 ) : ?>
            <?php
            //var_dump(get_class_methods($this->navigation()));  
            // Render the menu
            echo $this->navigation()
            ->menu()
            ->setContainer($this->quickAlbumNavigation)            
            ->render();
            ?>            
        <?php endif; ?>
        
        <?php if( count($this->quickVideoNavigation) > 0 ): ?>
            <?php
            //var_dump(get_class_methods($this->navigation()));  
            // Render the menu
            echo $this->navigation()
            ->menu()
            ->setContainer($this->quickVideoNavigation)            
            ->render();
            ?>
        <?php endif; ?>
        
        <ul>
            <li><a href="<?php echo $this->url(array('module' => 'classified', 'controller' => 'index', 'action' => 'getclassifieds', 'member' => $this->member_id), 'default', true) ?>" class="buttonlink">View Requirements</a></li>
        </ul>
        
        <?php echo $this->content()->renderWidget("user.profile-follow"); ?>        
        </div>
        </div>
        </div>
    </div>
    
    <div class="media-container width_70">
    <div class="videoBox" id="videoContent">
        <?php $uid = md5(time() . rand(1, 1000)) ?>
         <div class='video-border'>            
        <a href="<?php echo $this->url(array('module' => 'videos', 'controller' => 'manage'), 'default', true) ?>" style="text-decoration: none;"><h3>Videos</h3></a>   
        
        <?php if($this->videoCount >0): ?>
        <div class="seaocore_video_strips">
            <ul id="profile_videos" class="videos_browse profile_videos_<?php echo $uid ?>">            
                <?php foreach( $this->video_paginator as $item ): ?>
                <li>
                    <div class="video_thumb_wrapper">
                        <?php if ($item->duration):?>
                        <span class="video_length">
                            <?php
                            if( $item->duration>360 ) $duration = gmdate("H:i:s", $item->duration); else $duration = gmdate("i:s", $item->duration);
                            if ($duration[0] =='0') $duration = substr($duration,1); echo $duration;
                            ?>
                        </span>
                        <?php endif;?>
                        <?php
                        if( $item->photo_id ) {                    
                        echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'),array('class' => 'video_title'));
                        } else {
                        echo '<img alt="" class="video_title" src="' . $this->layout()->staticBaseUrl . 'application/modules/Video/externals/images/video.png">';
                        }
                        ?>                    
                    </div>
                    <div class='show_video_info'>
                        <a href='<?php echo $item->getHref();?>'><?php echo $item->getTitle();?></a>
                        <div class="video_author"><?php echo $this->translate('By');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?></div>
                        <div class="video_stats">
                            <span class="video_views"><?php echo $item->view_count;?> <?php echo $this->translate('views');?></span>
                            <?php if($item->rating>0):?>
                            <?php for($x=1; $x<=$item->rating; $x++): ?><span class="rating_star_generic rating_star"></span><?php endfor; ?><?php if((round($item->rating)-$item->rating)>0):?><span class="rating_star_generic rating_star_half"></span><?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>         
            <!--div class='video-pagging'>
                <div id="profile_videos_previous" class="paginator_previous profile_videos_previous_<?php echo $uid ?>">
                    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
                    'onclick' => '',
                    'class' => 'buttonlink icon_previous'
                    )); ?>
                </div>
                <div id="profile_videos_next" class="paginator_next profile_videos_next_<?php echo $uid ?>">
                    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
                    'onclick' => '',
                    'class' => 'buttonlink_right icon_next'
                    )); ?>
                </div>
             </div -->
            <!-- script type="text/javascript">
               en4.core.runonce.add(function(){
               var uid = '<?php echo $uid ?>';
                       var hasTitle = Boolean($$('.profile_videos_' + uid)[0].getParent().getElement('h3'));
                       <?php if (!$this->renderOne): ?>
                       var anchor = $$('.profile_videos_' + uid)[0].getParent();
                       $$('.profile_videos_previous_' + uid)[0].style.display = '<?php echo ( $this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
                       $$('.profile_videos_next_' + uid)[0].style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';
                       $$('.profile_videos_previous_' + uid)[0].removeEvents('click').addEvent('click', function(){
               en4.core.request.send(new Request.HTML({
               url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?> ,                    
                       data : {
               format : 'html',
                       subject : en4.core.subject.guid,
                       page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() - 1) ?>
               },                
                   evalScripts: true, /* this is the default */
                   onComplete: function(){
                       // first, only get elements that have an id and are divs
                           var filtered = this.response.elements.filter(function(el) {
                               if(el.getElementById('videoContent')){
                                   // if found, update html            
                                   $('videoContent').set("html", el.getElementById('videoContent').innerHTML);
                                   en4.sitevideoview.attachClickEvent(Array('video_title'));
                               }                            
                           });
                   }                                
               }), {
               'element' : anchor
               });                
               });
                       $$('.profile_videos_next_' + uid)[0].removeEvents('click').addEvent('click', function(){
               en4.core.request.send(new Request.HTML({
               url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?> ,                    
                       data : {
               format : 'html',
                       subject : en4.core.subject.guid,
                       page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
               },                
                   evalScripts: true, /* this is the default */
                   onComplete: function(){ 
                           // first, only get elements that have an id and are divs
                           var filtered = this.response.elements.filter(function(el) {
                                if(el.getElementById('videoContent')){
                                   // if found, update html            
                                   $('videoContent').set("html", el.getElementById('videoContent').innerHTML);
                                   en4.sitevideoview.attachClickEvent(Array('video_title'));
                               }                             
                           });        
                   }
               }), {
               'element' : anchor
               });

                       en4.core.runonce.add(function() {
               if (!hasTitle) {
               anchor.getElement('h3').destroy();
               }
               });                        

               });
                       <?php endif; ?>                    
               });
           </script -->
            </div>
        <?php else: ?>  
            <h4>Sorry no video(s) are available.</h4>
        <?php endif; ?>
        </div>
    </div>   
        <div class="photoBox" id="photoContent">
            <div class='photo-border'>
			<a href="<?php echo $this->url(array('module' => 'albums', 'controller' => 'manage'), 'default', true) ?>" style="text-decoration: none;"><h3>Photos</h3></a> 
            <?php 
             $puid = md5(time()+1 . rand(1, 1000));
            $this->headLink()
            ->appendStylesheet($this->layout()->staticBaseUrl
            . 'application/modules/Sitealbum/externals/styles/style_sitealbum.css')
            ?>
            <?php
            if($this->showLightBox):
            include_once APPLICATION_PATH . '/application/modules/Sitealbum/views/scripts/_lightboxPhoto.tpl';
            endif;
            $i=0;
            ?>
            <?php if($this->photoCount >0): ?>
            <?php if (empty($this->is_ajax)) : ?>
                <div id='photo_image_recent' class="seaocore_photo_strips">
                <?php endif; ?>	
                <ul class="thumbs thumbs_nocaptions profile_albums_<?php echo $puid ?>" id ="sitealbum_list_tab_photo_content">
                <?php foreach ($this->paginator as $item):?>
                    <li>                        
                        <a class="thumbs_photo" href="<?php echo $item->getHref(); ?>"  <?php /*if($this->showLightBox): ?>
                           onclick="openLightBoxAlbum('<?php echo $item->getPhotoUrl()?>','<?php echo Engine_Api::_()->sitealbum()->getLightBoxPhotoHref($item,array_merge($this->params, array('offset'=>$i))) ?>');return false;"
                      <?php endif; */ ?> >
                      
                           <span style="background-image: url(<?php echo $item->getPhotoUrl('thumb.normal'); ?>);"></span>
                         </a>
                         <span class="show_photo_des">                              
                                   <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>
                                   <br />                              
                                    <?php
                                      $owner = $item->getOwner();
                                      $parent = $item->getParent();
                                      echo $this->translate('By %1$s in %2$s',
                                          $this->htmlLink($owner->getHref(), $this->string()->truncate($owner->getTitle(),25)),$this->htmlLink($item->getHref(), $item->getTitle()));
                                    ?>
                         </span>                           
                </li>          
            <?php $i++; ?>
            <?php endforeach; ?>
            </ul>
            <!-- div>
                <div id="profile_albums_previous" class="paginator_previous">
                  <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
                    'onclick' => '',
                    'class' => 'buttonlink icon_previous'
                  )); ?>
                </div>
                <div id="profile_albums_next" class="paginator_next">
                  <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
                    'onclick' => '',
                    'class' => 'buttonlink_right icon_next'
                  )); ?>
                </div>
             </div -->
            <?php if (empty($this->is_ajax)) : ?>
        </div> 
            <?php endif; ?>            
                <!-- script type="text/javascript">
                      en4.core.runonce.add(function(){
                            <?php if( !$this->renderOne && $this->photoCount > 0): ?>
                            var uid = '<?php echo $puid ?>';
                            var anchor = $$('.profile_albums_' + uid)[0].getParent();
                            $('profile_albums_previous').style.display = '<?php echo ( $this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
                            $('profile_albums_next').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';

                            $('profile_albums_previous').removeEvents('click').addEvent('click', function(){
                              en4.core.request.send(new Request.HTML({
                                url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
                                data : {
                                  format : 'html',
                                  subject : en4.core.subject.guid,
                                  page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() - 1) ?>
                                },  
                                evalScripts: true, /* this is the default */
                                onComplete: function(){
                                    // first, only get elements that have an id and are divs
                                    var filtered = this.response.elements.filter(function(el) {                                        
                                            // if found, update html            
                                            if(el.getElementById('photoContent')){                                                                                  
                                                $('photoContent').set("html", el.getElementById('photoContent').innerHTML);                                                    
                                            }                       
                                    });
                                }   
                              }), {
                                'element' : anchor
                              })
                            });

                            $('profile_albums_next').removeEvents('click').addEvent('click', function(){
                              en4.core.request.send(new Request.HTML({
                                url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
                                data : {
                                  format : 'html',
                                  subject : en4.core.subject.guid,
                                  page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
                                },  
                                evalScripts: true, /* this is the default */
                                onComplete: function(){
                                    // first, only get elements that have an id and are divs
                                    var filtered = this.response.elements.filter(function(el) {
                                        if(el.getElementById('photoContent')){                                                                                  
                                            $('photoContent').set("html", el.getElementById('photoContent').innerHTML);                                                    
                                        }                            
                                    });
                                } 
                              }), {
                                'element' : anchor
                              })
                            });
                    <?php endif; ?>
                  });
                  
            var submit_topageprofile = true;
            function hideAlbumPhoto(photo_id)
            {
                submit_topageprofile = false;
                        en4.core.request.send(new Request.HTML({
                method: 'post',
                        'url': en4.core.baseUrl + 'widget/index/mod/sitealbum/name/photo-strips',
                        'data': {
                format: 'html',
                        'subject': '<?php echo $this->subject()->getGuid() ?>',
                        isajax: 1,
                        itemCountPerPage: '<?php echo $this->limit ?>',
                        hide_photo_id: photo_id
                },onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) {
                    $('photo_image_recent').innerHTML = responseHTML;
                }
                }));
                        return false;
             }

            function ShowPhotoPage(pageurl) {
                if (submit_topageprofile) {
                window.location = pageurl;
                }
                else {
                submit_topageprofile = true;
                }   
            }

            function opensmoothbox(url) {
                Smoothbox.open(url);
            }
            </script -->
             <?php else: ?>  
                <h4>Sorry no Album(s) are available.</h4>
            <?php endif; ?>
            </div>
    </div>   
    
                
    <div class="clear"></div>
</div>
<div class="info-container">
    <div class='info-border'>
    <h3>Info</h3>
    <?php echo ($this->fieldValueLoop($this->subject(), $this->fieldStructure)) ?>
    </div>
</div>
</div>

