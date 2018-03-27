Contributing to Okta Open Source Repos
======================================

Sign the CLA
------------

If you haven't already, [sign the CLA](https://developer.okta.com/cla/).  Common questions/answers are also listed on the CLA page.

Summary
-------
This document covers how to contribute to an Okta Open Source project. These instructions assume you have a GitHub.com account, so if you don't have one you will have to create one. Your proposed code changes will be published to your own fork of the Okta PHP Samples project and you will submit a Pull Request for your changes to be added.

_Lets get started!!!_


Fork the code
-------------

In your browser, navigate to: [https://github.com/okta/samples-php](https://github.com/okta/samples-php)

Fork the repository by clicking on the 'Fork' button on the top right hand side.  The fork will happen and you will be taken to your own fork of the repository.  Copy the Git repository URL by clicking on the clipboard next to the URL on the right hand side of the page under '**HTTPS** clone URL'.  You will paste this URL when doing the following `git clone` command.

On your computer, follow these steps to setup a local repository for working on the Okta PHP Samples:

``` bash
$ git clone https://github.com/YOUR_ACCOUNT/samples-php.git
$ cd samples-php
$ git remote add upstream https://github.com/okta/samples-php.git
$ git checkout master
$ git fetch upstream
$ git rebase upstream/master
```


Making changes
--------------

It is important that you create a new branch to make changes on and that you do not change the `master` branch (other than to rebase in changes from `upstream/master`).  In this example I will assume you will be making your changes to a branch called `feature/my-new-feature`.  This `feature/my-new-feature` branch will be created on your local repository and will be pushed to your forked repository on GitHub.  Once this branch is on your fork you will create a Pull Request for the changes to be added to the Okta PHP Samples project.

It is best practice to create a new branch each time you want to contribute to the project and only track the changes for that pull request in this branch.

``` bash
$ git checkout -b feature/my-new-feature
   (make your changes)
$ git status
$ git add .
$ git commit -a -m "descriptive commit message for your changes"
```

> The `-b` specifies that you want to create a new branch called `feature/my-new-feature`.  You only specify `-b` the first time you checkout because you are creating a new branch.  Once the `feature/my-new-feature` branch exists, you can later switch to it with only `git checkout feature/my-new-feature`.


Rebase `feature/my-new-feature` to include updates from `upstream/master`
------------------------------------------------------------

It is important that you maintain an up-to-date `master` branch in your local repository.  This is done by rebasing in the code changes from `upstream/master` (the official Okta PHP Samples project repository) into your local repository.  You will want to do this before you start working on a feature as well as right before you submit your changes as a pull request.  I recommend you do this process periodically while you work to make sure you are working off the most recent project code.

This process will do the following:

1. Checkout your local `master` branch
2. Synchronize your local `master` branch with the `upstream/master` so you have all the latest changes from the project
3. Rebase the latest project code into your `feature/my-new-feature` branch so it is up-to-date with the upstream code

``` bash
$ git checkout master
$ git fetch upstream
$ git rebase upstream/master
$ git checkout feature/my-new-feature
$ git rebase master
```

> Now your `feature/my-new-feature` branch is up-to-date with all the code in `upstream/master`.

Running E2E Tests locally before commits
----------------------------------------
E2E Tests can be run against the Custom Login, Okta-Hosted Login and Resource servers

Follow the steps below to run the tests locally:

```bash
# At project root
composer install
```
To test the samples you will need the following configured in your developer org:

* [A Web application](/okta-hosted-login#prerequisites)
* [A SPA application](https://github.com/okta/samples-js-angular/tree/master/okta-hosted-login#prerequisites)
* A test user account with a known username and password. Note that the USERNAME should be of the form "username@email.com"

Once you have those resources setup, export their details as the following environment variables:

```bash
export ISSUER=https://{yourOktaDomain}.com/oauth2/default
export CLIENT_ID={yourWebAppClientId}
export CLIENT_SECRET={yourWebAppClientSecret}
export SPA_CLIENT_ID={yourSpaAppClientId}
export USERNAME={userName}
export PASSWORD={password}
```

For Windows, please set the following environment variables:
- `ISSUER`
- `CLIENT_ID`
- `CLIENT_SECRET`
- `SPA_CLIENT_ID`
- `USER_NAME`
- `PASSWORD`

As an alternative you can provide the environment variables in a file named `testenv` in the root folder.

For example:

```
ISSUER=https://dev-12345.oktapreview.com/oauth2/default
CLIENT_ID=webclient123
CLIENT_SECRET=websecret123
SPA_CLIENT_ID=spaclient123
USERNAME=myuser@example.com
PASSWORD=mypassword
```

> **NOTE:** Windows has USERNAME as a built-in system variable, hence set the USER_NAME environment variable for testing.

Then run the E2E tests:

```bash
composer test
```

> **NOTE:** If you want to execute tests for okta-hosted-login, custom-login or resource-server in isolation, you can run the following scripts

```bash
composer test:okta-hosted-login
composer test:custom-login
composer test:resource-server
```

Make a GitHub Pull Request to contribute your changes
-----------------------------------------------------

When you are happy with your changes and you are ready to contribute them, you will create a Pull Request on GitHub to do so.  This is done by pushing your local changes to your forked repository (default remote name is `origin`) and then initiating a pull request on GitHub.

> **IMPORTANT:** Make sure you have rebased your `feature/my-new-feature` branch to include the latest code from `upstream/master` _before_ you do this.

``` bash
$ git push origin master
$ git push origin feature/my-new-feature
```

Now that the `feature/my-new-feature` branch has been pushed to your GitHub repository, you can initiate the pull request.

To initiate the pull request, do the following:

1. In your browser, navigate to your forked repository: [https://github.com/YOUR_ACCOUNT/samples-php](https://github.com/YOUR_ACCOUNT/samples-php)
2. Click the new button called '**Compare & pull request**' that showed up just above the main area in your forked repository
3. Validate the pull request will be into the upstream `master` and will be from your `feature/my-new-feature` branch
4. Enter a detailed description of the work you have done and then click '**Send pull request**'

If you are requested to make modifications to your proposed changes, make the changes locally on your `feature/my-new-feature` branch, re-push the `feature/my-new-feature` branch to your fork.  The existing pull request should automatically pick up the change and update accordingly.


Cleaning up after a successful pull request
-------------------------------------------

Once the `feature/my-new-feature` branch has been committed into the `upstream/master` branch, your local `feature/my-new-feature` branch and the `origin/feature/my-new-feature` branch are no longer needed.  If you want to make additional changes, restart the process with a new branch.

> **IMPORTANT:** Make sure that your changes are in `upstream/master` before you delete your `feature/my-new-feature` and `origin/feature/my-new-feature` branches!

You can delete these deprecated branches with the following:

``` bash
$ git checkout master
$ git branch -D feature/my-new-feature
$ git push origin :feature/my-new-feature
```
