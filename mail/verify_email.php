<?php
/**
 * Email template for email verification.
 */
use Yii\helpers\Html;
use Yii\helpers\Url;
?>

Please follow this link to complete the sign-up process.

<a href="http://countup.org/user/verify?email=<?=$email?>&code=<?=$code?>">Verify</a>