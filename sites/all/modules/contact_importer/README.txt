$Id: README.txt,v 1.1.4.7 2010/09/14 13:54:30 dalin Exp $

CONTACT IMPORTER
================

Contact Importer provides a form for your users to enter their email address and password. The
contacts from their address book are then retreived.

Contact Importer can be used with any Drupal form. No codding necessary. It can be used with 
an invite module, a user import module, an emailing module, or anything else that you can 
think of.

The current release interfaces with the Octazen or Open Inviter retrieval services. But the 
architecture is modular and can be very easily extended to use other retrieval services such 
as Contact Mines, ImproSys, or IpInvite. 


Developed By
------------

Advomatic LLC
http://advomatic.com

and

Scott Hadfield


Sponsored By
------------

Democrats.com
http://democrats.com


INSTALLATION
============

1) Unpack the module into your sites/all/modules directory.
2) Go to 
    Administer > Build > Modules
   Enable the Contact Importer module and at least one
   Retrieval Engine module.
3) Enable permisions at 
    Adminster > User > Permissions
   for 'administer contact importer' and 'access contact importer'.
4) Go to 
    Administer > Site configuration > Contact Importer
5) Review the documentation link for the Retrieval Engine(s) that you have enabled and install the
   libraries for at least one engine.
6) Choose which engines will manage which providers at
   Administer > Site configuration > Contact Importer > Providers
7) Integrate Contact Importer into your forms (see below).


CONSIDERATIONS
==============

It should be noted that using Contact Importer makes it very easy for your users to send email to
a lot of people.  It is recommended that you:
- have a CAPTCHA or a simlar method in place to prevent spambots from using your form to send
  spam.
- limit the number of emails/contacts that anonymous users can send/import.
- have a throttling and/or queuing mechanism in place so that if a user uses Contact Importer to insert
  500 email addresses into your textbox, your server is not brought to its knees when the form is
  submitted.


TROUBLESHOOTING
===============

If you change or add files or libraries, and your changes aren't reflected in Contact Importer,
try clearing the cache at:
Administer > Configure > Contact Importer > Settings


OPTIONAL EXTRAS
===============

Contact Importer works best in conjunction with either the Lightbox2 module or Automodal.
http://drupal.org/project/lightbox2
http://drupal.org/project/automodal

Contact Importer will use one of these modules to show a modal pop-up so that the user can import
from her address book without leaving the current page.  In the absence of Javascript, Contact
Importer degrades to a multistep-form-like method.


INTEGRATE WITH ANY FORM
=======================

1) Enable the Contact Importer Form Integration module
2) Enable Form ID and Field messaging at
    /admin/settings/contact_importer/settings
3) Navigate to the page containing your form.  You'll see messages listing each form ID and field
   name of all textfields and textareas on the page.  Note the form ID and field name that you want
   to use with Contact Importer.
4) Enter the form ID and field name at
    /admin/settings/contact_importer/forms
5) Disable Form ID and Field messaging at
    /admin/settings/contact_importer/settings


INTEGRATING WITH YOUR FORMS
===========================

For those concerned about ultimate performance you may not wish to suffer the slight PHP and
database overhead of the Contact Importer Form Integration module.  Instead you can bind Contact
Importer directly to your form element.  Here's an example textarea that we are binding with
Contact Importer.

<?php

  $form['some_form_element'] = array(
    '#type' => 'textarea',
    '#title' => t('Enter the email addresses of friends you would like to invite to sign'),
    '#description' => t('List of email addresses separated by commas or new lines. '),
  );

  // Add Contact Importer integration.
  if (module_exists('contact_importer')) {
    contact_importer_bind_to_form($form, 'some_form_element');
  }

?>

INTEGRATING WITH MORE SPECIFIC CUSTOMIZATION
============================================

If you require specific customizations you can imitate contact_importer_bind_to_form().  See the
comments in that function for more details.

In some cases you'll need more control over the format of the emails being imported.
For that you can use hook_contact_importer_contacts(&$list, $imported) to add extra
functionality, such as saving all imported email addresses in another db table (for
example). See the documentation on contact_importer_invoke() for more details.

WRITING OTHER RETRIEVAL ENGINES
===============================

Simply copy and re-name the octazen_engine or openinviter_engine directory and files.  Then edit
the files to work with the new retrieval service.  There are lots of in-line comments in the 
modules so it should be fairly straightforward.
