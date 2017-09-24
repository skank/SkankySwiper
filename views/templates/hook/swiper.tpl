<div class="swiper-container">
	<div class="swiper-wrapper">

	{foreach from=$swipers item=swiper}
        <div class="swiper-slide" >
            <img class="swiper-main-img" src="{$swiper['url_a']}" alt="">
            <img class="swiper-secondary-img" src="{$swiper['url_b']}" alt="">
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
var swiper;
document.addEventListener( 'DOMContentLoaded', function () {
    swiper = new Swiper('.swiper-container',{/literal}{$swiperConf|@json_encode nofilter}{literal});
}, false );
</script>


{/literal}