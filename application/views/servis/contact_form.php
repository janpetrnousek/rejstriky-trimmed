<h2>kontaktní údaje</h2>

<?php if (!isset($userinfo)) { ?>
<p class="pull-left">
    REGISTRUJTE SE <a href="registrace/rejstrikovy-servis">ZDE</a> A ZÍSKEJTE VÝHODY
<p>
<div class="tooltip tooltip--top plain">
    <span class="tooltip__handle information">?</span>
    <div class="tooltip__content" style="display: none;">
        <p>Výpis z rejstříku Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
            ut aliquip ex ea commodo consequat. </p>
    </div>
</div>    
<?php } ?>

<div class="form_control_group form_control_group--order">
    <div class="form_control_wrap">
        <input type="text" class="form_control" name="name" value="<?php echo set_value('name', isset($userinfo) ? $userinfo['name'] : ''); ?>"
                placeholder="* Jméno a příjmení / funkce či pozice" required/>
    </div>
    <div class="form_control_wrap">
        <input type="email" class="form_control" name="email" placeholder="* E-mail" value="<?php echo set_value('email', isset($userinfo) ? $userinfo['email'] : ''); ?>"
                required/>
    </div>
    <div class="form_control_wrap">
        <input type="text" class="form_control" name="phone" placeholder="* Telefon" value="<?php echo set_value('phone', isset($userinfo) ? $userinfo['phone'] : ''); ?>"
                required/>
    </div>
</div>


<div class="form_control_wrap form_control_wrap--padded">
    <input type="checkbox" class="rs_styled_checkbox" name="form_agree" value="1" id="form_agree" <?php echo set_checkbox('form_agree', 1); ?> />
    <label for="form_agree">
        <span class="btn_area"> </span>
        <span class="btn_desc">Souhlasím s <a href="files/podminky-uzivani-sluzby.pdf">obchodními podmínkami</a> a beru na vědomí zpracování osobních údajů</span>
    </label>
</div>
<p class="text-center">
    <input type="submit" class="form_submit" name="send" value="NEZÁVAZNĚ POPTAT"/>
</p>