# PHP BLOG CMS
## 📖 CONTENT MANAGEMENT FEATURES

- article management
- account management
- category management

### 👨‍💼 MANAGEMENT ROLES

- admin: fully management access
- author: article management access

### 👥 ACCOUNT FEATURED

- 2fa enabled by default on admin role
- email verification
- password reset
- remember me

### 🎇 EXTRA FEATURED

- tinymce integration editor
- self hosting images
> image path `assets\images\content\ARTICLE_ID`
- validation impose on form fields
- article meta tags
- pin article
- hide article
- social media sharing widget
- disqus integration comment system
- ads placeholder
> edit ads content at `partials\_sidebar_ads.php`
- articles search (by title or author name)
- pagination
- responsive design

### 🔧 INSTALLATION

1. create a database and import "**vision.sql**" file into that database. 
2. config `dash\auth\includes\dbh.php` file with your database information.
3. config **$base_url** inside of that same file. 
> $base_url will be used as a url prefix for sending full link of a verify by link feature<br>
> e.g. `http://localhost/dash/auth/includes/2fa.php?verify_token=392605&verify`
5. config mailing setting in `dash\auth\includes\sendmail.php` with your own email credential information, 
> it's required, otherwise PHPMailer won't work. if you're using gmail account, it's recommended to turn on 2FA and use "App passwords" instead of turn on "allow less secure apps" and directly using your account password.
5. default password to log into dashboard is **`Admin123`** with email **`fapito6771@d3ff.com`** 
> since admin role is 2FA enabled by nature, you'll have to manually update the email to yours, in other to received 2FA email. 
6. run the below query 
> don't forget to adjust the query to match with your own email, you'll be using that email to log into dashboard)
```
UPDATE users
SET email = "YOUREMAIL@GMAIL.COM"
WHERE id = 19;
```
6. go to localhost/YOUR_BLOG_DIRECTORY/dash to log into dashboard.

### 📷 SCREENSHOTS

<img src="https://github.com/lynchzival/php-blog-cms/blob/main/screenshot/1.png?raw=true" width="auto" height="250" />
<img src="https://github.com/lynchzival/php-blog-cms/blob/main/screenshot/2.png?raw=true" width="auto" height="250" />
<img src="https://github.com/lynchzival/php-blog-cms/blob/main/screenshot/3.png?raw=true" width="auto" height="250" />
<img src="https://github.com/lynchzival/php-blog-cms/blob/main/screenshot/4.png?raw=true" width="auto" height="250" />
