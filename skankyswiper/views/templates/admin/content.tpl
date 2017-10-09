<div class="swiper-admin col-md-10 col-md-offset-1">
	<div class="swiper-title">
		<label class="title-label">Texte page d'accueil</label>
		<input class="title-input" name="title" value="{$title}" type="text" placeholder="un text qui irra sur le slideshow">
	</div>
	<div class="swiper-admin-layout">
		<div class="sortable swiper-form">
			{foreach from=$swipers item=swiper}
			<div class="swiper-group">
				<div class="swiper-block-a">
					<div class="swiper-block-img">
						<input type="hidden" class="input-id" name="_id" value="{$swiper['id_s_swiper']}">
						<label class="swiper-label">url image a</label>
						<input class="swiper-input input-a" name="url_a" value="{$swiper['url_a']}" type="text">
					</div>
					<div class="swiper-block-alt">
						<input type="checkbox" name="style_a" value="1" {if $swiper['style_a']}checked{/if}>
						<label for="style_a">Cadre</label>
					</div>
					<div class="swiper-block-text">
						<label class="swiper-label" for="text_a">Description</label>
						<input class="swiper-input input-a" type="text" name="text_a" value="{$swiper['text_a']}" placeholder="Une phrase qui decrit l'image">
					</div>
				</div>
				<div>
					<div class="swiper-block-img">
						<label class="swiper-label">url image b</label>
						<input class="swiper-input input-b" name="url_b" value="{$swiper['url_b']}" type="text">
					</div>
					<div class="swiper-block-alt">
						<input type="checkbox" name="style_b" value="1" {if $swiper['style_b']}checked{/if}>
						<label for="style_b">Cadre</label>
					</div>
					<div class="swiper-block-text">
						<label class="swiper-label" for="text_b">Description</label>
						<input class="swiper-input input-a" type="text" name="text_b" value="{$swiper['text_b']}" placeholder="Une phrase qui decrit l'image">
					</div>
				</div>
				<div class="bootstrap">
					<button class="btn btn-default remove-btn">
						<i class="material-icons">delete</i> <br>Suprimer
					</button>
				</div>
			</div>
			{/foreach}
		</div>
		<div class="swiper-upload">
			<div class="swiper-upload-btn">
				<label class="swiper-label">Upload</label>
				<input type="file" class="swiper-input-file" id="input-img" name="files[]">
				<button class="btn btn-default pull-left add-btn-file"  data-url="{$ajaxUrl}">
					<i class="material-icons">add_circle_outline</i> <br>Ajouter
				</button>
			</div>
			<div class="swiper-upload-list">
				{foreach from=$imgList item=img}
					{if $img != '.' && $img != '..'  && !( preg_match("/^big-\w*/",$img))}
					<div class="img-select">
						<span class="img-trash" data-img="{$img}"><i class="material-icons">delete</i></span>
						<img src="{$uri}upload/skankyswiper/{$img}" alt="{$img}" width="100" height="100"><br>
						{$img}
					</div>

					{/if}
				{/foreach}
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="bootstrap swiper-footer">
		<button class="btn btn-default pull-left add-btn">
			<i class="material-icons">add_circle_outline</i> <br>Ajouter
		</button>
		<button class="btn btn-default pull-right save-btn" data-url="{$ajaxUrl}">
			<i class="process-icon-save"></i> Enregistrer
		</button>
	</div>
	<div class="clear"></div>
</div>
<div class="model" style="display:none">
	<div class="swiper-group">
		<div class="swiper-block-a">
			<div class="swiper-block-img">
				<input type="hidden" class="input-id" name="_id" value="-1">
				<label class="swiper-label">url image a</label>
				<input class="swiper-input input-a" name="url_a" value="" type="text">
			</div>
			<div class="swiper-block-alt">
				<input type="checkbox" name="style_a" value="1">
				<label for="style_a">Cadre</label>
			</div>
			<div class="swiper-block-text">
				<label class="swiper-label" for="text_a">Description</label>
				<input class="swiper-input input-a" name="text_a" value="" type="text" placeholder="Une phrase qui decrit l'image">
			</div>
		</div>
		<div>
			<div class="swiper-block-img">
				<label class="swiper-label">url image b</label>
				<input class="swiper-input input-b" name="url_b" value="" type="text">
			</div>
			<div class="swiper-block-alt">
				<input type="checkbox" name="style_b" value="1">
				<label for="style_b">Cadre</label>
			</div>
			<div class="swiper-block-text">
				<label class="swiper-label" for="text_b">Description</label>
				<input class="swiper-input input-a" name="text_b" value="" type="text" placeholder="Une phrase qui decrit l'image">
			</div>
		</div>
		<div class="bootstrap">
			<button class="btn btn-default remove-btn">
				<i class="material-icons">delete</i> <br>Suprimer
			</button>
		</div>
	</div>
</div>

{literal}
<style>
	.swiper-title{
		padding: 16px;
		margin: 0 32px;
	}
	.title-label{
		width:30%!important;
		display: inline-block!important;
		font-weight: normal!important;
		line-height: 40px;
		text-align: right;
	}
	.title-input{
		display: inline-block;
		width: 48%;
		height: 31px;
		padding: 8px!important;
		font-size: 12px;
		line-height: 1.42857;
		color: #555;
		background-color: #F5F8F9;
		background-image: none;
		border: 1px solid #C7D6DB;
		border-radius: 3px;
		transition: border-color ease-in-out 0.15s,box-shadow ease-in-out 0.15s;
	}

	.swiper-label{
		width: 80px!important;
		display: inline-block!important;
		font-weight: normal!important;
		line-height: 40px;
	}
	.swiper-input-file{
		display: inline-block;
		height: 31px;
		padding: 8px!important;
		font-size: 12px;
		line-height: 1.42857;
		color: #555;
		background-color: #F5F8F9;
		background-image: none;
		border: 1px solid #C7D6DB;
		border-radius: 3px;
		transition: border-color ease-in-out 0.15s,box-shadow ease-in-out 0.15s;
	}
	.swiper-input{
		display: inline-block;
		width: 180px;
		height: 31px;
		padding: 8px!important;
		font-size: 12px;
		line-height: 1.42857;
		color: #555;
		background-color: #F5F8F9;
		background-image: none;
		border: 1px solid #C7D6DB;
		border-radius: 3px;
		transition: border-color ease-in-out 0.15s,box-shadow ease-in-out 0.15s;
	}
	.swiper-group{
		width: 96%;
		display: flex;
		flex-direction: row;
		justify-content: space-around;
		margin: 16px 0;
		padding: 8px;
		border: 1px solid #C7D6DB;
		border-radius: 3px;
		background-color: #F5F8F9;
	}
	.swiper-admin-layout{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
	}
	.swiper-form{
		border: 1px solid #C7D6DB;
		border-radius: 3px;
		padding-left: 8px;
		flex-grow: 1;
	}
	.swiper-upload{
		width: 400px;
		border: 1px solid #C7D6DB;
		border-radius: 3px;
	}
	.swiper-footer{
		margin-top: 16px;
		width: 90%;
	}
	.swiper-admin{
		position: relative;
		left: -25px;
	}
	.swiper-upload-list{
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		padding: 5px;
		max-height: 500px;
		overflow-y: auto;
	}
	.img-select{
		display: inline-block;
		width: 110px;
		height: 120px;
		padding: 5px;
		overflow: hidden;
		position: relative;
	}
	.img-trash{
		position: absolute;
		top: 2px;
		right: 2px;
		cursor: pointer;
	}
	.img-trash:hover{
		color: red;
	}
	#input-img {
		vertical-align: top;
		margin-top: 2px;
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('.save-btn').on('click',function(e){
		var link = $(this).attr('data-url');
		link+= "&action=save";
		postData = [];
		$('.swiper-form').children('.swiper-group').each(function(k){
			data = {};
			data.id = parseInt($(this).find('input[name=_id]').val());
			data.url_a = $(this).find('input[name=url_a]').val();
			data.style_a = $(this).find('input[name=style_a]').prop("checked");
			data.text_a = $(this).find('input[name=text_a]').val();
			data.url_b = $(this).find('input[name=url_b]').val();
			data.style_b = $(this).find('input[name=style_b]').prop("checked");
			data.text_b = $(this).find('input[name=text_b]').val();
			data.position = k;
			postData.push(data);
		});
		valuForm = JSON.stringify(postData);
		var title = $('input[name=title]').val();

		$.post(link,{data: valuForm,title:title},function(result){
			location.reload();
		})
	})
	
	$('.sortable').sortable();

	$('.add-btn').on('click',function(e){
		var text = $('.model').html();
		$('.swiper-form').append(text);

	});

	$('.swiper-form').on('click','.remove-btn',function(e){
		console.log($(this).parent('.swiper-group'));
		var id = $(this).parents('.swiper-group').find('input[type=hidden]').val();
		var link = $('.save-btn').attr('data-url');
		me = $(this);
		link+= "&action=delete";
		$.post(link,{id:id},function(data){
			data = JSON.parse(data);
			me.parents('.swiper-group').remove();
		});
	});
	
	var fileList = [];
	
	$('#input-img').on('change',function(e){
		//console.log(e.target.files);
		fileList = [];
		list = e.target.files;
		for (var i = 0; i < e.target.files.length; i++) {
			if(e.target.files[i].size<=5242880){
				var type = e.target.files[i].type;
				if(type.startsWith('image')){
					$('.upload-list').append('<li>'+e.target.files[i].name+'</li>');
					fileList.push(e.target.files[i]);
				}else{
					alert(e.target.files[i].name + ' n\'est pas une image');
				}
			}else{
				alert(e.target.files[i].name + ' est trop volumineux');
			}
		}
	});
	$(".add-btn-file").on('click',function(e){
		var link = $(this).attr('data-url');
		link += "&action=upload";
		upload(fileList,link,0);
	});
	$('.swiper-upload-list').on('click','.img-trash',function(e){
		var link = $(".add-btn-file").attr('data-url');
		link += "&action=delImg";
		var img = $(this).attr('data-img');
		var me = $(this);
		$.post(link,{img:img},function(data){
			data = JSON.parse(data);
			me.parents('.img-select').remove();
		});
	});
	function upload(files,link,index){
		var xhr = new XMLHttpRequest();
		var file = files[index];
		xhr.upload.onprogress = function(e){};

		xhr.onload = function(e){

			var retour = jQuery.parseJSON(e.target.responseText);
			if(retour.statu){
				$('.swiper-upload-list').append(retour.message);
				if(index < files.length-1){
					upload(files,link,index+1);
				}else{
					fileList = [];
					$('#input-img').val('');
				}
			}else{
				alert(retour.message);
				fileList = [];
			}
		};

		xhr.open('POST',link);
		xhr.setRequestHeader('conten-type','multipart/form-data');
		xhr.setRequestHeader('X-File-Type',file.type);
		xhr.setRequestHeader('X-File-Size',file.size);
		xhr.setRequestHeader('X-File-Name',file.name);
		xhr.send(file);
	}


});
</script>
{/literal}