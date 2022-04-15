# PHP Sample Applications for Okta

> NOTICE: We're excited about the acquisition of Auth0 to bring you better support in PHP. This repo will be placed into security patch only mode and we will not be adding any further features. If you are looking for an API that is not supported in this library, please call the API directly. Our documentation for the supported Management APIs are located here: https://developer.okta.com/docs/reference/core-okta-api/. Please reach out to the [DevForum](https://devforum.okta.com/) for any questions.

This repository contains several sample applications that demonstrate various Okta use-cases in your PHP application.

Please find the sample that fits your use-case from the table below.

| Sample                                  | Description |
|-----------------------------------------|-------------|
| [Okta-Hosted Login](/okta-hosted-login) | A PHP application that will redirect the user to the Okta-Hosted login page of your Org for authentication.  The user is redirected back to the PHP application after authenticating. |
| [Custom Login Page](/custom-login)      | A PHP application that uses the Okta Sign-In Widget within the PHP application to authenticate the user. |
| [Resource Server](/resource-server)     | This is a sample API resource server that shows you how to authenticate requests with access tokens that have been issued by Okta. |

## Requirements
These samples require a few items from you.  First, you will need to have a system that can run PHP 7.0+. Websites should be able to be hosted on `localhost:8080` and your user should have access to run the command `php -S localhost:8080`.  You will not have to manually run this, but the command you do run in the sample will ultimately run this for you.

We depend on other packages to have the samples run. To install these dependencies, we will be using [composer](https://getcomposer.org).  You can get information on how to install this and run it at their website.
