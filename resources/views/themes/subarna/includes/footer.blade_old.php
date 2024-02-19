<footer>
<div class="container">
        <div class="row">
    <div class="col-xs-12">
        <img
            src="{{ URL::asset('/themes/'.$theme.'/assets/img/logofooter.png') }}"
            alt="Subarna"
            class="img-responsive" style="max-height: 50px; margin:0 auto;">
    </div>
        <div class="col-xs-12">
            <ul class="footer-menu">
                <li><a title="{{ trans($theme.'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.about_us')  ?>">{{ trans($theme.'-app.foot.about_us') }}</a></li>
                <li><a title="{{ trans($theme.'-app.foot.contact') }}" href="<?= \Routing::translateSeo(trans($theme.'-app.links.contact')) ?>">{{ trans($theme.'-app.foot.contact') }}</a></li>
                @if(!empty($has_subasta))
                  <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme.'-app.foot.auctions')}}</a></li>
                @endif
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a></li>
                @endif

                <li><a title="{{ trans($theme.'-app.foot.legal') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.privacy')?>">{{ trans($theme.'-app.foot.legal') }}</a></li>

                <li><a title="{{ trans($theme.'-app.foot.term_condition') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition')?>">{{ trans($theme.'-app.foot.term_condition') }}</a></li>


            </ul>
        </div>
        <div class="col-xs-12">
            <?php
                   $empre= new \App\Models\Enterprise;
                   $empresa = $empre->getEmpre();
            ?>

            <address>
                     <?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?> <br>
                     <?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?><br>
                     <?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?> <?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> </br>
                     <?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?> - <a title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>" href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"><?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?></a>
            </address>

        </div>
    </div>

</div>
<div id="cookies-message" class="cookies-message d-flex align-items-center justify-content-space-between" style="display: none">
    <div>
        {!! trans($theme.'-app.msg_neutral.cookie_law') !!}
    </div>
    <button class="cookies-btn" id="cookies-btn">{{ trans($theme.'-app.home.confirm') }}</button>
</div>
    </footer>
<div class="copy">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12" style="display: flex; flex-wrap: wrap">
				<p style="margin: 5px auto 5px 0px;">
					<span>&copy; <?= trans($theme.'-app.foot.rights') ?></span>
				</p>
				<p style="margin: 5px 0px;"><a class="color-letter" role="button" title="{{ trans($theme.'-app.foot.developedSoftware') }}" href="{{ trans($theme.'-app.foot.developed_url') }}" target="no_blank">{{ trans($theme.'-app.foot.developedBy') }}</a></p>
			</div>
		</div>
	</div>
</div>



<script>
    if(localStorage.cookies !== 'true'){
        $("#cookies-message").show()
    }
    $('#cookies-btn').click(function(){
        $('#cookies-message').hide();
        localStorage.cookies = 'true'
    });
</script>
