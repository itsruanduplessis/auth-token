# Auth Token

## Background

Auth Token is a simple custom Drupal module used by me (Ruan du Plessis) to showcase several
Drupal concepts in action. The idea was to write a module that contains concise, maintainable and
scalable code thats easy to read and efficient to run while following Drupal community best practice
guidelines along the way.

## Introduction

When installed, Auth Token will create and install an Auth Token field (`field_auth_token`) on the
user entity and use batch processing concepts to populate each user in the site with a unique 32 character
authentication token. The module will then authenticate users who navigate to the site with a matching auth token
within the URL query parameters.

sample URL:
> https://www.umami.docksal.site?authtoken="[TOKEN_HERE]"

## How It Works
1. The Drupal configuration API is used to create and install the auth token field when the module is enabled.
2. Batch API is used during module install to safely update user entities with a 32bit authentication token.
3. An event subscriber is then used to "listen" for an authentication token and authenticate a user once one is found.
4. Once the module is enabled, all newly created users will automatically get an Auth token created for them.
