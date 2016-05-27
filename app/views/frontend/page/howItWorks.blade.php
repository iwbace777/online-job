@extends('frontend.layout')
@section('main')
    @if(Session::get('locale') == 'sk')
    <?php $name = 'name2'; ?>
    @else
    <?php $name = 'name'; ?>
    @endif
    <main class="bs-docs-masthead" role="main">
        <div style="background: #D6EBFC; padding-bottom: 50px;">
            <div class="container">
                <div class="row margin-top-lg">
                    <h1 class="text-center color-blue">{{ trans('page.how_it_works') }}</h1>
                </div>
                <div class="row margin-top-normal">
                    <div class="col-sm-6">
                        <div class="text-center color-blue"><h2>{{ trans('page.business_owners') }}</h2></div>
                        <div class="row margin-top-normal">
                            <div class="col-sm-2 text-center">
                                <img src="/assets/img/services_step01.png">
                            </div>
                            <div class="col-sm-10 color-gray-light">
                                <h3 class="color-blue" style="margin-top: 5px;">{{ trans('page.create_your_profile') }}</h3>
                                <p>{{ trans('page.msg01') }}</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="row margin-top-normal">
                            <div class="col-sm-2 text-center">
                                <img src="/assets/img/services_step02.png">
                            </div>
                            <div class="col-sm-10 color-gray-light">
                                <h3 class="color-blue" style="margin-top: 5px;">{{ trans('page.start_bidding') }}</h3>
                                <p>{{ trans('page.msg02') }}</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="row margin-top-normal">
                            <div class="col-sm-2 text-center">
                                <img src="/assets/img/services_step03.png">
                            </div>
                            <div class="col-sm-10 color-gray-light">
                                <h3 class="color-blue" style="margin-top: 5px;">{{ trans('page.get_hired') }}</h3>
                                <p>{{ trans('page.msg03') }}</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="row margin-top-normal">
                            <div class="col-sm-2 text-center">
                                <img src="/assets/img/services_step04.png">
                            </div>
                            
                            <div class="col-sm-10 color-gray-light">
                                <h3 class="color-blue" style="margin-top: 5px;">{{ trans('page.grow_your_business') }}</h3>
                                <p>{{ trans('page.msg04') }}</p>
                            </div>
                        </div>                                                             
                    </div>
                    
                     <div class="col-sm-6">
                        <div class="text-center color-blue"><h2>{{ trans('page.consumers') }}</h2></div>
                        <div class="row margin-top-normal">
                            <div class="col-sm-2 text-center">
                                <img src="/assets/img/customers_step01.png">
                            </div>
                            <div class="col-sm-10 color-gray-light">
                                <h3 class="color-blue" style="margin-top: 5px;">{{ trans('page.post_a_job') }}</h3>
                                <p>{{ trans('page.msg05') }}</p>
                            </div>
                        </div>
                        
                        <div class="row margin-top-normal">
                            <div class="col-sm-2 text-center">
                                <img src="/assets/img/customers_step02.png">
                            </div>
                            <div class="col-sm-10 color-gray-light">
                                <h3 class="color-blue" style="margin-top: 5px;">{{ trans('page.compare_options') }}</h3>
                                <p>{{ trans('page.msg06') }}</p>
                            </div>
                        </div>
                        
                        <div class="row margin-top-normal">
                            <div class="col-sm-2 text-center">
                                <img src="/assets/img/customers_step03.png">
                            </div>
                            <div class="col-sm-10 color-gray-light">
                                <h3 class="color-blue" style="margin-top: 5px;">{{ trans('page.choose_your_service_provider') }}</h3>
                                <p>{{ trans('page.msg07') }}</p>
                            </div>
                        </div>
                        
                        <div class="row margin-top-normal">
                            <div class="col-sm-2 text-center">
                                <img src="/assets/img/customers_step04.png">
                            </div>
                            <div class="col-sm-10 color-gray-light">
                                <h3 class="color-blue" style="margin-top: 5px;">{{ trans('page.get_job_done_hassle_free') }}</h3>
                                <p>{{ trans('page.msg08') }}</p> 
                            </div>
                        </div>                                                             
                    </div> 
                </div>
            </div>
        </div>
        <div style="background-image: url(/assets/img/how_it_works.jpg); background-size: cover;">
            <div class="container">
                <div class="margin-top-normal">&nbsp;</div>                
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text-center margin-top-lg">
                        <iframe style="width: 100%;height: 400px;" src="//www.youtube.com/embed/x_eDQnglQ9g" frameborder="0" allowfullscreen=""></iframe>
                    </div>
                </div>
                <div class="margin-top-lg">&nbsp;</div>
            </div>
        </div>
    </main>
@stop

@stop
