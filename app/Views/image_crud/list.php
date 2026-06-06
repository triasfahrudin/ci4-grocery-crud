<?php
/**
 * Image CRUD - Gallery List View
 *
 * Renders the gallery with uploader, thumbnails, titles, ordering, and delete.
 *
 * Available variables:
 *   $crud              - ImageCrud library instance
 *   $photos            - Array of photo objects
 *   $primary_key       - Primary key field name
 *   $title_field       - Title field name (or null)
 *   $unset_delete      - Whether delete is disabled
 *   $unset_upload      - Whether upload is disabled
 *   $has_priority_field - Whether ordering is enabled
 *   $upload_url        - URL for file uploads
 *   $ajax_list_url     - URL for AJAX gallery list reload
 *   $ordering_url      - URL for reordering
 *   $insert_title_url  - URL for saving title
 */

// Register CSS files
$crud->setCss('assets/image_crud/css/fineuploader.css');
$crud->setCss('assets/image_crud/css/photogallery.css');
$crud->setCss('assets/image_crud/css/colorbox.css');

// Register JS files (vendor libs are loaded by the controller/page)
// Only register the inline view - vendor libs loaded via CDN in controller

/** @var \App\Libraries\ImageCrud $crud */
?>
<script>
$(function(){
	<?php if ( ! $unset_upload) {?>
		createUploader();
	<?php }?>
		loadColorbox();
});
function loadColorbox()
{
	$('.color-box').colorbox({
		rel: 'color-box'
	});
}
function loadPhotoGallery(){
	$.ajax({
		url: '<?= $ajax_list_url ?>',
		cache: false,
		dataType: 'text',
		beforeSend: function()
		{
			$('.file-upload-messages-container:first').show();
			$('.file-upload-message').html("<?= $crud->l('loading') ?>");
		},
		complete: function()
		{
			$('.file-upload-messages-container').hide();
			$('.file-upload-message').html('');
		},
		success: function(data){
			$('#ajax-list').html(data);
			loadColorbox();
		}
	});
}

function createUploader() {
	var uploader = new qq.FineUploader({
		element: document.getElementById('fine-uploader'),
		request: {
			 endpoint: '<?= $upload_url ?>'
		},
		validation: {
			 allowedExtensions: ['jpeg', 'jpg', 'png', 'gif', 'webp']
		},
		callbacks: {
			 onComplete: function(id, fileName, responseJSON) {
				 loadPhotoGallery();
			 }
		},
		debug: true,
	});
}

function saveTitle(data_id, data_title)
{
	  	$.ajax({
			url: '<?= $insert_title_url; ?>',
			type: 'post',
			data: {primary_key: data_id, value: data_title},
			beforeSend: function()
			{
				$('.file-upload-messages-container:first').show();
				$('.file-upload-message').html("<?= $crud->l('saving_title');?>");
			},
			complete: function()
			{
				$('.file-upload-messages-container').hide();
				$('.file-upload-message').html('');
			}
			});
}

window.onload = createUploader;

</script>
<?php if(!$unset_upload){ ?>
<div id="fine-uploader"></div>
<?php }?>
<div class="clear"></div>
<div id='ajax-list'>
	<?php if(!empty($photos)){?>
	<script type='text/javascript'>
		$(function(){
			$('.delete-anchor').click(function(){
				if(confirm('<?= $crud->l("alert_delete");?>'))
				{
					$.ajax({
						url:$(this).attr('href'),
						beforeSend: function()
						{
							$('.file-upload-messages-container:first').show();
							$('.file-upload-message').html("<?= $crud->l('deleting');?>");
						},
						success: function(){
							loadPhotoGallery();
						}
					});
				}
				return false;
			});
			$(".color-box img").mousedown(function(){
				return false;
			});
    		$(".photos-crud").sortable({
        		handle: '.move-box',
        		opacity: 0.6,
        		cursor: 'move',
        		revert: true,
        		update: function() {
    				var order = $(this).sortable("serialize");
	    				$.post("<?= $ordering_url?>", order, function(theResponse){});
    			}
    		});
    		$('.ic-title-field').keyup(function(e) {
    			if(e.keyCode == 13) {
					var data_id = $(this).attr('data-id');
					var data_title = $(this).val();

					saveTitle(data_id, data_title);
    			}
    		});

    		$('.ic-title-field').bind({
    			  click: function() {
    				$(this).css('resize','both');
    			    $(this).css('overflow','auto');
    			    $(this).animate({height:80},600);
    			  },
    			  blur: function() {
      			    $(this).css('resize','none');
      			  	$(this).css('overflow','hidden');
      			  	$(this).animate({height:20},600);

					var data_id = $(this).attr('data-id');
					var data_title = $(this).val();

					saveTitle(data_id, data_title);
    			  }
    		});
		});
	</script>
	<ul class='photos-crud'>
	<?php foreach($photos as $photo_num => $photo){?>
			<li id="photos_<?= $photo->$primary_key; ?>">
				<div class='photo-box'>
					<a href='<?= $photo->image_url?>' <?php if (isset($photo->title)) {echo 'title="'.str_replace('"',"&quot;",$photo->title).'" ';}?>target='_blank' class="color-box" rel="color-box" tabindex="-1"><img src='<?= $photo->thumbnail_url?>' width='90' height='60' class='basic-image' /></a>
					<?php if($title_field !== null){ ?>
					<textarea class="ic-title-field" data-id="<?= $photo->$primary_key; ?>" autocomplete="off" ><?= htmlspecialchars($photo->$title_field ?? ''); ?></textarea>
					<div class="clear"></div><?php }?>
					<?php if($has_priority_field){?><div class="move-box"></div><?php }?>
					<?php if(!$unset_delete){?><div class='delete-box'>
						<a href='<?= $photo->delete_url?>' class='delete-anchor' tabindex="-1"><?= $crud->l('list_delete');?></a>
					</div><?php }?>
					<div class="clear"></div>
				</div>
			</li>
	<?php }?>
		</ul>
		<div class='clear'></div>
	<?php }?>
</div>
