# [Question2Answer](http://www.question2answer.org/) Single Sign-on with [Admidio](https://www.admidio.org/)

Please read [Installing Question2Answer with single sign-on](https://docs.question2answer.org/install/single-sign-on/) before proceeding.

Question2Answer (q2a) can delegate its entire user management to a third party. Here we will delegate q2a user management to Admidio. Q2A has a [framework](https://docs.question2answer.org/install/single-sign-on/) to make it happen. This kind of framework allows an application to be semi-embedded into another application. Many small applications should be embeddable into another bigger application. It will improve user experience across multiple applications. A user centric application like Admidio should become a host for many embeddable applications.

## Q2A-Admidio Configuration

Both Q2A and Admidio will be sharing the same database access with each prefixed table set. It means both applications will be installed in the same web host. In this configuration, Admidio will be installed in the main domain ([https://agromaya.net/](https://agromaya.net/)), and q2a will be installed in a sub-domain ([https://ask.agromaya.net/](https://ask.agromaya.net/)). You can have a different configuration. The idea is that both applications can share the same user session. Admidio keeps user session in cookie. So, q2a needs to access the same cookie. You need to set QA_COOKIE_DOMAIN in *qa-config.php* to point to Admidio domain *.agromaya.net* (must keep the leading period). Domain-subdomain cookie accessibility can be tricky. You may need to see [Allow php sessions to carry over to subdomains](https://stackoverflow.com/questions/644920/allow-php-sessions-to-carry-over-to-subdomains).

### Admidio Session Cookie

Admidio maintains user sessions in database through the *adm_sessions* table. And the *session id* is kept in $&lowbar;COOKIE with a formatted name as follow:

**[ADMIDIO&lowbar;&lt;*Admidio-organization-name*&gt;&lowbar;&lt;*database-name*&gt;&lowbar;&lt;*Admidio-table-prefix*&gt;&lowbar;SESSION_ID]**

The angle brackets are placeholders which are separated by underscores.

The cookie name indicates that a user can have simultaneous sessions with multiple Admidio organizations at the same time. However, there can only be one user session per organization. The cookie name starts with ADMIDIO&lowbar; and ends with &lowbar;SESSION_ID. We can just ignore anything in between and assume there is only one Admidio user for a device. The *session id* will lead us to the *user id* in the Admidio *adm_sessions* table. And the *user id* will lead us to all other user data such as user name, email address, and photo.
