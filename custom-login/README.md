# PHP + Custom Login Example
This example shows you how to use PHP to login to your application with an Custom Login page.  The login is achieved with the [Okta Sign In Widget][], which gives you more control to customize the login experience within your app.  After the user authenticates they are redirected back to the application with an access code that is then exchanged for an access token.

## Prerequisites

Before running this sample, you will need the following:

* An Okta Developer Account, you can sign up for one at https://developer.okta.com/signup/.
* An Okta Application, configured for Web mode. This is done from the Okta Developer Console and you can find instructions [here][OIDC WEB Setup Instructions].  When following the wizard, use the default properties.  They are are designed to work with our sample applications.

## Running This Example
After completing the steps in the [root projects Readme][], follow the remainder of these steps.

1. Change directory into this sample folder. `cd custom-login`
2. Copy the distributed `.env.dist` file to `.env`.  `cp .env.dist .env`
3. Fill in the environment variables from the Application you created during the prerequisites
4. Go back to the root of this project. `cd ../`
5. Run the sample with composer `composer sample:custom-login`
6. Visit https://localhost:8080

If you see a home page that prompts you to login, then things are working! Clicking the Log in button will redirect you to the Okta hosted sign-in page.

You can login with the same account that you created when signing up for your Developer Org, or you can use a known username and password from your Okta Directory.

> Note: If you are currently using your Developer Console, you already have a Single Sign-On (SSO) session for your Org. You will be automatically logged into your application as the same user that is using the Developer Console. You may want to use an incognito tab to test the flow from a blank slate.


[Okta Sign In Widget]: https://github.com/okta/okta-signin-widget
[OIDC WEB Setup Instructions]: https://developer.okta.com/authentication-guide/implementing-authentication/auth-code#1-setting-up-your-application
[root projects readme]: /okta/samples-php/#installing