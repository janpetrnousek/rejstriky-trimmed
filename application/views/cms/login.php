<main>
    <form class="login" action="<?php echo current_url(); ?>" method="post">
		<h1>
			rejstříky.info - CMS login
		</h1>
		<label for="username">
			Jméno:
		</label>
		<br/>
		<input type="text" id="username" name="username" value="" autofocus />

		<p></p>

		<label for="password">
			Heslo:
		</label>
		<br/>
		<input type="password" id="password" name="password" value="" />
		
		<p></p>
		
		<span>
			<input type="submit" value="Přihlásit se" class="submit" name="login" />
		</span>
	</form>
</main>