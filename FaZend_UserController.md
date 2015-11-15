# Sample Usage #

In your `layouts/scripts/layout.phtml` add this line:

```
<?=$this->action('login', 'user', 'fazend')?>
```

In your `application/config` directory you should create forms:

```
formLogin.ini (email, pwd, sumbit)
formRegisterAccount.ini (email, password, submit)
formRemindPassword.ini (email, submit)
```

Create email templates in `application/emails`:

```
AccountRegistered.tmpl (user)
adminNewUserRegistered.tmpl (user)
RemindPassword.tmpl (user)
```

Create view scripts in `application/views/scripts/user`:

```
register.phtml (form)
remind.phtml (form)
reminded.phtml
```

Create database table: `user (id, email, password)`.

That's it.