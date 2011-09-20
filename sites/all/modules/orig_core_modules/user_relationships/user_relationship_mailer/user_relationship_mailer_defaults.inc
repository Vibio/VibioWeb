<?php
// $Id: user_relationship_mailer_defaults.inc,v 1.1.2.10 2010/01/03 19:46:49 alexk Exp $
/**
 * @file User Relationships Mailer implementation. Contains all default strings and action definitions
 */

global $_user_relationship_mailer_ops;
$_user_relationship_mailer_ops = array('request', 'cancel', 'approve', 'disapprove', 'remove', 'pre_approved');

/**
 * #515338 Translations of $_user_relationship_mailer_ops above, used on the admin settings form. Keep the two in sync when adding new operations.
 */
function _user_relationship_mailer_ops_translations() {
  return array(
    'request'       => t('Request'), 
    'send_request'       => t('Send Request messages'), 
    'cancel'        => t('Cancel'), 
    'send_cancel'        => t('Send Cancel messages'), 
    'approve'       => t('Approve'), 
    'send_approve'       => t('Send Approve messages'), 
    'disapprove'    => t('Disapprove'), 
    'send_disapprove'    => t('Send Disapprove messages'), 
    'remove'        => t('Remove'), 
    'send_remove'        => t('Send Remove messages'), 
    'pre_approved'  => t('Pre-approved'),
    'send_pre_approved'  => t('Send Pre-approved messages'),
  );
}

/**
 * Replacements for mail messages
 * @param relationship relationship object for which emails are being sent
 */
function user_relationship_mailer_replacements(&$relationship) {
  $requester = $relationship->requester;
  $requestee = $relationship->requestee;
  //load relationship type, if necessary
  if (!isset($relationship->relationship_type)) {
    $relationship->relationship_type = user_relationships_type_load($relationship->rtid);
  }
  $replacements = array(
    '@site'                     => variable_get('site_name', 'Drupal'),
    '@site_url'                 => $GLOBALS['base_url'],

    '@requester_name'           => $requester->name,
    '@requester_link'           => theme('user_relationships_user_link', $requester->uid),
    '@requester_uid'            => $requester->uid,
    '@requester_list_link'      => url("user/{$requester->uid}/relationships/list", array('absolute' => TRUE)),
    '@requester_pending_link'   => url("user/{$requester->uid}/relationships/requests", array('absolute' => TRUE)),

    '@requestee_name'           => $requestee->name,
    '@requestee_link'           => theme('user_relationships_user_link', $requestee->uid),
    '@requestee_uid'            => $requestee->uid,
    '@requestee_list_link'      => url("user/{$requestee->uid}/relationships/list", array('absolute' => TRUE)),
    '@requestee_pending_link'   => url("user/{$requestee->uid}/relationships/requests", array('absolute' => TRUE)),

    '@relationship_name'        => $relationship->relationship_type->name,
    '@relationship_plural_name' => $relationship->relationship_type->plural_name,
  
    '@elaboration'              => isset($relationship->elaboration) ? $relationship->elaboration : '',
  );
  
  // #443326 Integration with realname module
  if (module_exists('realname')) {
    $replacements['@requester_realname'] = $requester->realname;
    $replacements['@requestee_realname'] = $requestee->realname;
  }

  //'remove' action needs a few more variables for the template
  if (isset($relationship->deleted_by)) {
    $deleted_by = $relationship->deleted_by;
    $replacements['@deleted_by_name'] = $deleted_by->name;
    $replacements['@deleted_by_link'] = url("user/{$deleted_by->uid}", array('absolute' => TRUE));
    $replacements['@deleted_by_uid']  = $deleted_by->uid;
  }

  return $replacements;
}

/**
 * Default request relationship message
 *
 * Changed in code to Jenny's 20110916 update; will likely need to change
 *  in database as well.
 */
function user_relationship_mailer_request_default() {
  $subject = "[@site] @requester_name wants to be connected to you on Vibio";
  $message = <<<MESSAGE
Hi @requestee_name,

@requester_name wants to be connected to you on Vibio.

Here's a link to @requester_name's profile:
  @requester_link

Whether or not you’re elated or deflated by this request, you’ll have to follow this link to accept or reject it: @requestee_pending_link.

Thanks!
The Vibio team

This message was sent to you because you have relationship email notifications turned on. If you no longer wish to receive these emails, turn them off  @site_url/user/@requestee_uid/edit.
MESSAGE;

  return array(
    'subject' => $subject,
    'message' => $message,
  );
}

/**
 * Default cancel request message
 */
function user_relationship_mailer_cancel_default() {
  $subject = "[@site] @requester_name has canceled their @relationship_name request";
  $message = <<<MESSAGE
Dear @requestee_name,

@requester_name has canceled their @relationship_name request.

Regards,
The @site team

This message was sent to you because you have user relationships email notifications turned on.
If you no longer wish to receive these emails, you can turn them off at @site_url/user/@requestee_uid/edit.
MESSAGE;

  return array(
    'subject' => $subject,
    'message' => $message,
  );
}

/**
 * Default approve to relationship message
 */
function user_relationship_mailer_approve_default() {
  $subject = "[@site] You are @requestee_name's newest @relationship_name";
  $message = <<<MESSAGE
Dear @requester_name,

@requestee_name has approved your request to be their @relationship_name.

Here's a link to your relationships:
  @requester_list_link

Regards,
The @site team

This message was sent to you because you have user relationships email notifications turned on.
If you no longer wish to receive these emails, you can turn them off at @site_url/user/@requester_uid/edit.
MESSAGE;

  return array(
    'subject' => $subject,
    'message' => $message,
  );
}

/**
 * Default disapprove to relationship message
 */
function user_relationship_mailer_disapprove_default() {
  $subject = "[@site] @requestee_name has declined your relationship request";
  $message = <<<MESSAGE
Dear @requester_name,

@requestee_name has declined your request to be their @relationship_name.

Regards,
The @site team

This message was sent to you because you have user relationships email notifications turned on.
If you no longer wish to receive these emails, you can turn them off at @site_url/user/@requester_uid/edit.
MESSAGE;

  return array(
    'subject' => $subject,
    'message' => $message,
  );
}

/**
 * Default removed relationship message
 */
function user_relationship_mailer_remove_default() {
  $subject = "[@site] @relationship_name relationship between @requester_name and @requestee_name has been deleted";
  $message = <<<MESSAGE
Dear @target_name,

@requester_name's @relationship_name relationship to @requestee_name has been deleted by @deleted_by_name.

Regards,
The @site team

This message was sent to you because you have user relationships email notifications turned on.
If you no longer wish to receive these emails, you can turn them off at @site_url/user/@profile_uid/edit.
MESSAGE;

  return array(
    'subject' => $subject,
    'message' => $message,
  );
}

/**
 * Default no approval request relationship message
 */
function user_relationship_mailer_pre_approved_default() {
  $subject = "[@site] New @relationship_name relationship between @requester_name and @requestee_name";
  $message = <<<MESSAGE
Dear @target_name,

We have just created a new @relationship_name relationship between @requester_name and @requestee_name.

Regards,
The @site team

This message was sent to you because you have user relationships email notifications turned on.
If you no longer wish to receive these emails, you can turn them off at @site_url/user/@profile_uid/edit.
MESSAGE;

  return array(
    'subject' => $subject,
    'message' => $message,
  );
}