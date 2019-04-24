<div id="share-dialog" title="Sdílet">

    <?php
        $share_url = urlencode(current_url());
    ?>

    <a href="mailto:?subject=Sdilet&body=<?php echo $share_url; ?>" title="sdílet přes e-mail">
        <img src="images/share-email.png" alt="" />
    </a>

    <a href="sms:?body=<?php echo $share_url; ?>" title="sdílet přes sms">
        <img src="images/share-sms.png" alt="" />
    </a>

    <a href="https://web.whatsapp.com/send?text=<?php echo $share_url; ?>" title="sdílet přes whatsapp" target="_blank">
        <img src="images/share-whatsapp.png" alt="" />
    </a>

    <a href="fb-messenger://share/?link=<?php $share_url; ?>&app_id=123456789" title="sdílet přes messenger" target="_blank">
        <img src="images/share-messenger.png" alt="" />
    </a>

    <a href="https://www.facebook.com/sharer.php?t=ShareThis&u=<?php echo $share_url; ?>" title="sdílet přes facebook" target="_blank">
        <img src="images/share-facebook.png" alt="" />
    </a>
    
    <a href="https://twitter.com/intent/tweet?text=ShareThis&url=<?php echo $share_url; ?>" title="sdílet přes twitter" target="_blank">
        <img src="images/share-twitter.png" alt="" />
    </a>

    <a href="https://www.linkedin.com/shareArticle/?title=ShareThis&url=<?php echo $share_url; ?>" title="sdílet přes linkedin" target="_blank">
        <img src="images/share-linkedin.png" alt="" />
    </a>
    
</div>