@extends('main')
    @section('page-styles')
        {{ HTML::style('//fonts.googleapis.com/css?family=Bitter') }}
        {{ HTML::style('/assets/metronic/frontend/layout/css/style.css') }}
        {{ HTML::style('/assets/metronic/frontend/layout/css/style-responsive.css') }}
        {{ HTML::style('/assets/metronic/frontend/layout/css/themes/blue.css') }}
        {{ HTML::style('/assets/css/style_frontend.css') }}    
    @stop

    @section('body')
        <body class="corporate">
            @section('header')
                <div class="header">
                    <div class="container">
                        <a class="site-logo" href="/">
                            <img src="/assets/img/logo.png" alt="Inquirymall Frontend">
                        </a>
                        <div class="pull-left padding-top-xs">
                            <i class="fa fa-globe color-white font-size-xl"></i>
                            &nbsp;
                            <div class="btn-group">
								<button type="button" class="btn btn-default btn-sm">
								    @if (Session::get('locale') == 'sk')
							            {{ 'Slovak' }}
						            @else
						                {{ 'English' }}
					                @endif 
							    </button>
								<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
								<ul class="dropdown-menu" role="menu">
									<li> <a href="{{ URL::route('language-chooser', 'en') }}"> English</a> </li>
									<li> <a href="{{ URL::route('language-chooser', 'sk') }}"> Slovak</a> </li>
								</ul>
							</div>                            
                            
                        </div>
                        <a href="javascript:void(0);" class="mobi-toggler"><i class="fa fa-bars"></i></a>
                        <?php
                            if (!isset($pageNo)) {
                                $pageNo = 0;
                            } 
                        ?>
                        <div class="header-navigation pull-right font-transform-inherit">
                            <ul>
                                <li class="dropdown {{ ($pageNo == 3) ? 'active' : '' }}">
                                    <a href="{{ URL::route('job.post') }}">
                                        {{ trans('page.post_a_job') }}
                                    </a>
                                </li>
                                
                                <li class="dropdown {{ ($pageNo == 5) ? 'active' : '' }}">
                                    <a href="{{ URL::route('job.search') }}">
                                        {{ trans('page.find_jobs') }}
                                    </a>
                                </li>
                                
                                <li class="dropdown {{ ($pageNo == 6) ? 'active' : '' }}">
                                    <a href="{{ URL::route('user.search') }}">
                                        {{ trans('page.find_companies') }}
                                    </a>
                                </li>                                
                                
                                @if (Session::has('user_id'))
                                <li class="dropdown {{ ($pageNo == 4) ? 'active' : '' }}">
                                    <a href="{{ URL::route('job.dashboard') }}">
                                        {{ trans('page.dashboard') }}
                                    </a>
                                </li>                                
                                <li class="dropdown">
                                    <a href="{{ URL::route('user.logout') }}">
                                        {{ trans('page.sign_out') }}
                                    </a>
                                </li>                                
                                @endif
                                
                                @if (!(Session::has('user_id')))
                                <li class="dropdown {{ ($pageNo == 1) ? 'active' : '' }}">
                                    <a href="{{ URL::route('user.login') }}">
                                        {{ trans('page.login') }}
                                    </a>                                    
                                </li>
                                <li class="dropdown {{ ($pageNo == 2) ? 'active' : '' }}">
                                    <a href="{{ URL::route('user.signup') }}">
                                        {{ trans('page.register') }}
                                    </a>                                    
                                </li>
                                @endif
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            @show

            @section('main')
            
            @show

            @section('footer')

            <div class="pre-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4 pre-footer-col">
                          <h2>{{ trans('page.company_info') }}</h2>
                          <ul>
                            <li><a href="http://www.mediatel.sk/o-nas-marketing-na-mieru/" target="_blank">{{ trans('page.about_us') }}</a></li>
                            <li><a href="http://www.mediatel.sk/novinky/" target="_blank">{{ trans('page.blog') }}</a></li>
                            <li><a href="http://www.mediatel.sk/kariera-online/" target="_blank">{{ trans('page.careers') }}</a></li>
                            <li><a href="{{ URL::route('page.howItWorks') }}">{{ trans('page.how_it_works') }}?</a></li>
                            <li><a href="http://www.mediatel.sk/kontakt/" target="_blank">{{ trans('page.contact_support') }}</a></li>
                          </ul>
                        </div>
                      
                        <div class="col-sm-4 pre-footer-col">
                          <h2>{{ trans('page.our_contacts') }}</h2>
                          <address class="margin-bottom-40">
                          MEDIATEL spol. s r.o.<br>
                          Miletičova 21, 821 08 Bratislava<br>
                          02/ 501 02 888 | fax: 02/ 501 02 870<br>
                          {{ trans('page.website') }} : <a href="http://www.mediatel.sk">www.mediatel.sk</a><br>
                          {{ trans('page.email') }} : info@mediatel.sk<br>
                          IČO: 35 859 415 | IČ DPH: SK2021728258<br>
                          </address>
                        </div>
                        <div class="col-sm-4 pre-footer-col">
                            <div class="pre-footer-subscribe-box pre-footer-subscribe-box-vertical">
                                <h2>{{ trans('page.newsletter') }}</h2>
                                <p>{{ trans('page.msg_subscribe') }}</p>
                                
                                <div class="input-group">
                                    <input type="text" placeholder="{{ trans('page.enter_your_email') }}" class="form-control" id="js-text-subscriber-email">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" id="js-btn-subscriber">{{ trans('page.subscribe') }}</button>
                                    </span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 padding-top-10">
                        &copy; 2015 Tvorba webových stránok spoločnosti MEDIATEL | Vlastník značky Zlaté Stránky
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <ul class="social-footer list-unstyled list-inline pull-right">
                                <li><a href="http://facebook.com/mediatel.sk" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="http://twitter.com/mediatelsk" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.linkedin.com/company/mediatel-spol-s-r-o-slovenska-republika" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            </ul>  
                        </div>
                    </div>
                </div>
            </div>
            @show
        </body>
    @stop

    @section('page-scripts')
        {{ HTML::script('/assets/metronic/frontend/layout/scripts/back-to-top.js') }}
        {{ HTML::script('/assets/metronic/frontend/layout/scripts/layout.js') }}
        {{ HTML::script('/assets/metronic/global/plugins/bootbox/bootbox.min.js') }}
        <script type="text/javascript">
            jQuery(document).ready(function() {
                Layout.init();
                Layout.initUniform();
                Layout.initTwitter();
            });

            function validateEmail(email) {
                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                return re.test(email);
            }            

            $(document).ready(function() {
                $("button#js-btn-subscriber").click(function() {
                    var email = $("input#js-text-subscriber-email").val();
                    if (validateEmail(email)) {
                        $.ajax({
                            url: "{{ URL::route('async.user.doSubscriber') }}",
                            dataType : "json",
                            type : "POST",
                            data : { email : email },
                            success : function(result){
                                bootbox.alert(result.msg);
                                window.setTimeout(function(){
                                    bootbox.hideAll();
                                }, 2000);
                            }
                        });
                    } else {
                        bootbox.alert("{{ trans('user.invalid_email')}}");
                        window.setTimeout(function(){
                            bootbox.hideAll();
                        }, 2000);                        
                    }
                });
            });
        </script>       
    @stop
@stop
