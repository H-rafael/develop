<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>


<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">设置插件</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">首页</a></li>
                            <li class="breadcrumb-item active" aria-current="page">设置插件</li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="main col">
            <div class="body container card">
                <div class="card-header">
                    <h3 class="mb-0"> <div class="typecho-page-title">
                            <?php include 'page-title.php'; ?>
                        </div>
                    </h3>
                </div>
                <div class="row typecho-page-main" role="form">
                    <div class="col-mb-12 col-tb-8 col-tb-offset-2">
                        <?php Typecho_Widget::widget('Widget_Plugins_Config')->config()->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer-html.php';?>
</div>


<?php
include 'common-js.php';
include 'form-js.php';
include 'footer.php';
?>
