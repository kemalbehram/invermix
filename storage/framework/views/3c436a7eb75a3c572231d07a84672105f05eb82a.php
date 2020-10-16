<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="host" content="<?php echo e(url('')); ?>">
        <title><?php echo e(env('APP_NAME')); ?> | Make transaction</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
       
       <link rel="stylesheet" href="<?php echo e(asset('css/coinpayment.css')); ?>">
    </head>
    <body style="font-family:<?php echo e(config('coinpayment.font.family')); ?>;">
        <div class="container">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        
        <script src="<?php echo e(asset('js/coinpayment.js')); ?>"></script>
    </body>
</html>
<?php /**PATH /home/superloc/dynamis.me/maxprofit.dynamis.me/core/vendor/hexters/coinpayment/src/Providers/../Resources/views/layouts/master.blade.php ENDPATH**/ ?>