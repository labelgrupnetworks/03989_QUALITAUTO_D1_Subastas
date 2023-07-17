<section class="works-section">
    <div class="row">
        <div class="col-12 col-lg-8 offset-lg-2 ps-lg-5">
            <h3 class="ff-highlight fs-32-40 mb-3 mb-lg-5">
                {{ trans("$theme-app.foot.work_with_us") }}
            </h3>
            <p style="max-width: 400px" class="mb-4 mb-lg-5">
                {{ trans("$theme-app.home.careers-text") }}
            </p>

            <div class="works">
                <a class="no-decoration" href="{{ Routing::translateSeo('workwithus/workwithus-arte') }}">
                    <p class="py-3 border-top border-bottom d-flex justify-content-between align-items-center">
                        <span>
                            {{ trans("$theme-app.home.workwithus_arte") }}
                        </span>
                        <span class="border rounded-circle">
                            @include('components.boostrap_icon', [
                                'icon' => 'arrow-right-short',
                                'size' => '40',
                            ])
                        </span>
                    </p>
                </a>
                <a class="no-decoration" href="{{ Routing::translateSeo('workwithus/workwithus-joyeria') }}">
                    <p class="py-3 border-bottom d-flex justify-content-between align-items-center">
                        <span>
                            {{ trans("$theme-app.home.workwithus_joyeria") }}
                        </span>
                        <span class="border rounded-circle">
                            @include('components.boostrap_icon', [
                                'icon' => 'arrow-right-short',
                                'size' => '40',
                            ])
                        </span>
                    </p>
                </a>
            </div>
        </div>
    </div>

</section>
