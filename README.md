## Workers (Required)

These 2 command should always run and restart after code update.

- To clear all temporary file in the temp directory of s3 bucket, and other commands to run.
    - `php artisan schedule:work`
- To send mail and other queue works:
    - `php artisan queue:work`

## Docusign info:

- Form id must be updated based on the type in the docusign helper
- Signer email field will always pass from api and it should be read only in webform.
- Component api name for signer email and title will always be case sensitive. These will be api name in the web form.
    - `Signer_email` : Fixed for signer email
    - `Title_Name` : Fixed for title name
    - `Title_Type` : Fixed for title type
    - `Term_Start_Date` : Fixed for title type
    - `Term_End_Date` : Fixed for title type
    - `Title_Length` : Fixed for title length
    - `Title_License_Territories` : Fixed for title territories
    - `Revenue_Type` `(Pay_Per_View, Avod, Avod_Buy_Up)` : Fixed for revenue type (Radio button in template) Name and Value
  
  
