# [Question2Answer](http://www.question2answer.org/) Single Sign-on with [Admidio](https://www.admidio.org/)

Please read [Installing Question2Answer with single sign-on](https://docs.question2answer.org/install/single-sign-on/) before proceeding.

Question2Answer (q2a) can delegate its entire user management to a third party. Here we will delegate q2a user management to Admidio. Q2A has a [framework](https://docs.question2answer.org/install/single-sign-on/) to make it happen. This kind of framework allows an application to be semi-embedded into another application. Many small applications should be embeddable into another bigger application. It will improve user experience across multiple applications. A user centric application like Admidio should become a host for many embeddable applications.

## Q2A-Admidio Configuration

Both Q2A and Admidio will be sharing the same database access with each table set prefixed. It means both applications will be installed in the same web host. In this configuration, Admidio will be installed in the main domain ([https://agromaya.net/](https://agromaya.net/)), and q2a will be installed in a sub-domain ([https://ask.agromaya.net/](https://ask.agromaya.net/)). You can have a different configuration. The idea is that both applications can share the same user session. Admidio keeps user session in cookie. So, q2a needs to access the same cookie. You need to set QA_COOKIE_DOMAIN in *qa-config.php* to point to Admidio domain *.agromaya.net* (must keep the leading period). Domain-subdomain cookie accessibility can be tricky. You may need to see [Allow php sessions to carry over to subdomains](https://stackoverflow.com/questions/644920/allow-php-sessions-to-carry-over-to-subdomains).
