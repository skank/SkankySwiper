<div class="swiper-title"><h1>{$swiperTitle}</h1></div>
<div class="swiper-container">
	<div class="swiper-wrapper">

	{foreach from=$swipers item=swiper}
		<div class="swiper-slide" >
			{if $swiper['url_a'] != ''}
			<img class="swiper-main-img {$swiper['style_a']}" 
				src="{$swiper['url_a']}"
				srcset="{$swiper['url_a']} {$minWidth}w,
					{$swiper['url_a_big']} {$bigWidth}w,"
				alt="{$swiper['text_a']}">
			{/if}
			{if $swiper['url_b'] != ''}
			<img class="swiper-secondary-img {$swiper['style_b']}" 
				src="{$swiper['url_b']}"
				srcset="{$swiper['url_b']} {$minWidth}w,
					{$swiper['url_b_big']} {$bigWidth}w,"
				alt="{$swiper['text_b']}">
			{/if}
		</div>
	{/foreach}
	</div>
	{if !empty($swiperConf['pagination'])}
		<div class="swiper-pagination"></div>
	{/if}
	{if !empty($swiperConf['nextButton'])}
		<div class="swiper-button-prev"></div>
		<div class="swiper-button-next"></div>
	{/if}
	</div>

{literal}
<script type="text/javascript">
document.addEventListener( 'DOMContentLoaded', function () {
	
	var swiper = {};
	var swiperConf = {/literal}{$swiperConf|@json_encode nofilter}{literal};
	/*
	Ajouter des configuration au swiper

		swiperConf.slidesPerView = 3;
		swiperConf.spaceBetween = 30;

	 */
	swiper = new Swiper('.swiper-container',swiperConf);
	/*
	contenu d un swiper
		$swiper = [
			'id_s_swiper' => l id
			'url_a'       => url normal a 
			'url_a_big'   => url big a
			'style_a'     => si case cocher le nom de la special class
			'text_a'      => alt pour img a
			'url_b'       =>  url normal b
			'url_b_big'   => url big 
			'style_b'     => si case cocher le nom de la special class
			'text_b'      => alt pour img a
			'position'    => l order d affichage 
		]
	 */
}, false );
</script>
{/literal}