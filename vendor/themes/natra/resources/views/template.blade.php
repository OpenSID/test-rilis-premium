<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    @include("layouts.commons.meta")
    <!-- </head> -->
</head>
<body onLoad="renderDate()">
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container" style="background-color: #f6f6f6;">
    <header id="header">
        @include("layouts.partials.header")
    </header>
    <div id="navarea">
        @include("layouts.partials.menu_head")
    </div>
    <div class="row">
        <section>
            <div class="content_bottom">
                <div class="col-lg-9 col-md-9">
                    @include("layouts.partials.bottom_content_left")
                </div>
                <div class="col-lg-3 col-md-3">
                    @include("layouts.partials.bottom_content_right")
                </div>
            </div>
        </section>
    </div>
</div>
<footer id="footer">
    @include("layouts.partials.footer_top")
    @include("layouts.partials.footer_bottom")
</footer>
@include("layouts.commons.meta_footer")
@stack('scripts')
</body>
</html>