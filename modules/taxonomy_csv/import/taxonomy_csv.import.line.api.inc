<?php
// $Id: taxonomy_csv.import.line.api.inc,v 3.1.2.1 2010/08/16 10:52:40 danielkm Exp $

/**
 * @file
 * Process import of a csv line, i.e. of a term or a list of terms.
 */

/**
 * Process the import of items.
 *
 * @param $line
 *   Array which contains items of a cleaned and checked csv line.
 * @param $options
 *   An associative array of import options:
 *   - import_format  : see _taxonomy_csv_values('list_import_format')
 *   - vocabulary_id  : vocabulary id to import into
 *   - existing_items : see _taxonomy_csv_values('list_import_option')
 *   // Specific to relations import:
 *   - relations_create_subrelations: boolean.
 *   - relations_all_vocabularies   : boolean.
 * @param $previous_items
 *   (Optional) Cleaned and checked previous imported line names and tids array.
 *   Needed with some contents as one term array structure or Taxonomy manager.
 *   Specificities:
 *   - taxonomy_manager:
 *       tid array is indexed with old tids.
 *   - def_links:
 *       'name' and 'tid' arrays contain sub-arrays 'vocabulary' and 'term' with
 *       imported identifiant as key and name or tid/vid as value in order to
 *       allow duplicate names import and use of number identifiants.
 *
 * @return Result array:
 *   'name' => array of imported terms names,
 *   'tid'  => array of imported terms tids,
 *   'msg'  => array of message codes.
 */
function taxonomy_csv_line_import($line, $options, $previous_items = array()) {
  // Define default values.
  $result = array(
    'name' => array(),
    'tid'  => array(),
    'msg'  => array(),
  );

  // Only count check because function variables are already checked.
  if (count($line)) {
    switch ($options['import_format']) {
      case TAXONOMY_CSV_FORMAT_ALONE_TERMS:
      case TAXONOMY_CSV_FORMAT_DEFINITIONS:
      case TAXONOMY_CSV_FORMAT_DESCRIPTIONS:
      case TAXONOMY_CSV_FORMAT_WEIGHTS:
      case TAXONOMY_CSV_FORMAT_SYNONYMS:
        $term = (object) array(
          'name' => $line[0],
          'vid'  => $options['vocabulary_id'],
        );

        switch ($options['import_format']) {
          case TAXONOMY_CSV_FORMAT_DEFINITIONS:
            $term->weight      = $line[1];
            $term->description = $line[2];
            $term->synonyms    = array_slice($line, 3);
            break;

          case TAXONOMY_CSV_FORMAT_DESCRIPTIONS:
            $term->description = $line[1];
            break;

          case TAXONOMY_CSV_FORMAT_WEIGHTS:
            $term->weight = $line[1];
            break;

          case TAXONOMY_CSV_FORMAT_SYNONYMS:
            $term->synonyms = array_slice($line, 1);
            break;
        }
        // Import term then store result. No check because only one term.
        $current_result = taxonomy_csv_term_import($term, $options['existing_items']);
        $result['name'][] = $current_result['name'];
        $result['tid'][]  = $current_result['tid'];
        $result['msg'] = $current_result['msg'];
        break;

      case TAXONOMY_CSV_FORMAT_DEFINITION_LINKS:
        // 1. Keep previous imported vocabularies and terms.
        $result['name'] = $previous_items['name'];
        $result['tid']  = $previous_items['tid'];

        // 2. Define main term to be imported.
        $term = (object) array(
          'name'        => $line[0],
          'description' => $line[3],
          'weight'      => $line[4],
          'parents'     => array(),
          'synonyms'    => array_slice($line, 9, $line[5]),
          'relations'   => array(),
        );

        // 3. Prepare links. Zero character ids have been already removed.
        $parents_names  = array_slice($line, 9 + $line[5], $line[6]);
        $children_names = array_slice($line, 9 + $line[5] + $line[6], $line[7]);
        $related_names  = array_slice($line, 9 + $line[5] + $line[6] + $line[7], $line[8]);
        $related_vocs   = array_slice($line, 9 + $line[5] + $line[6] + $line[7] + $line[8], $line[8]);

        // 4. Check if main and related vocabularies are already in memory.
        $vocabularies = array_unique(array_merge(array($line[2]), $related_vocs));
        foreach ($vocabularies as $id) {
          // Empty vocabulary identifiant is saved too.
          // Vocabulary is unknown.
          if (!isset($result['name']['vocabulary'][$id])) {
            $vid = taxonomy_csv_vocabulary_get_id($id);
            // Vocabulary exists.
            if ($vid) {
              $vocabulary['name'] = $id;
              $vocabulary['vid']  = $vid;
            }
            // Vocabulary doesn't exist.
            else {
              $vocabulary = (array) taxonomy_csv_vocabulary_create($id);
              $result['msg'][] = 610; // Info new vocabulary.
            }
            // Remember imported vocabulary.
            $result['name']['vocabulary'][$id] = $vocabulary['name'];
            $result['tid']['vocabulary'][$id]  = $vocabulary['vid'];
          }
        }
        // Update main term with vocabulary id.
        $term->vid = $vid = $result['tid']['vocabulary'][$line[2]];

        // 5. Import each parent and check result.
        $tids = array();
        foreach ($parents_names as $key => $id) {
          // Check if term is already imported.
          if (!isset($result['name']['term'][$id])) {
            $current_result = taxonomy_csv_term_import(
              (object) array(
                'name' => $id,
                'vid'  => $vid,
              ),
              $options['existing_items']);
            $result['name']['term'][$id] = $current_result['name'];
            $result['tid']['term'][$id]  = $current_result['tid'];
            $result['msg'] = array_merge($result['msg'], $current_result['msg']);
            if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
              break 2;
            }
          }
          $tids[] = $result['tid']['term'][$id];
        }
        // Update main term with parents ids.
        $term->parents = $tids;

        // 6. Import each related term and check result.
        $tids = array();
        foreach ($related_names as $key => $id) {
          // Check if term is already imported or not.
          if (isset($result['name']['term'][$id])) {
            // Check vocabulary: need update if it changes (by line approach).
            $temp_term       = taxonomy_csv_term_find((object) array('tid' => $result['tid']['term'][$id]));
            $temp_vocabulary = ($related_vocs[$key] == '') ?
                // There is no vocabulary identifiant, so use main term vid.
                $vid :
                // There is a vocabulary identifiant.
                $result['tid']['vocabulary'][$related_vocs[$key]];
            // Vocabulary change is unadvised if term has links, but possible.
            if ($temp_term->vid != $temp_vocabulary) {
              $current_result = taxonomy_csv_term_import(
                (object) array(
                  'name' => $result['name']['term'][$id],
                  'vid'  => $temp_vocabulary,
                ),
                $options['existing_items']);
              $result['name']['term'][$id] = $current_result['name'];
              $result['tid']['term'][$id]  = $current_result['tid'];
              $result['msg'] = array_merge($result['msg'], $current_result['msg']);
              if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
                break 2;
              }
              $result['msg'][] = 511; // Notice: Update not recommended.
            }
          }
          else {
            $current_result = taxonomy_csv_term_import(
              (object) array(
                'name' => $id,
                'vid'  => ($related_vocs[$key] == '') ?
                    // There is no vocabulary identifiant, so use main term vid.
                    $vid :
                    // There is a vocabulary identifiant.
                    $result['tid']['vocabulary'][$related_vocs[$key]],
              ),
              $options['existing_items']);
            $result['name']['term'][$id] = $current_result['name'];
            $result['tid']['term'][$id]  = $current_result['tid'];
            $result['msg'] = array_merge($result['msg'], $current_result['msg']);
            if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
              break 2;
            }
          }
          $tids[] = $result['tid']['term'][$id];
        }
        // Update main term with related ids.
        $term->relations = $tids;

        // 7. Import main term and check result.
        // If main term has an identifiant, pre-import it.
        if ($line[1] != '') {
          // If term is already imported, simply update main term with tid.
          if (isset($result['name']['term'][$line[1]])) {
            $term->tid = $result['tid']['term'][$line[1]];
          }
          // Pre-import is needed only when name and identifiant are different.
          elseif (drupal_strtolower($line[0]) != drupal_strtolower($line[1])) {
            $current_result = taxonomy_csv_term_import(
              (object) array(
                'name' => $term->name,
                'vid'  => $term->vid,
              ),
              TAXONOMY_CSV_EXISTING_IGNORE_CREATE);
            $result['name']['term'][$line[1]] = $current_result['name'];
            $result['tid']['term'][$line[1]]  = $current_result['tid'];
            $result['msg'] = array_merge($result['msg'], $current_result['msg']);
            if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
              break;
            }
            $term->tid = $current_result['tid'];
          }
        }
        // Import main term.
        $current_result = taxonomy_csv_term_import($term, $options['existing_items']);
        // Save with name if identifiant was not set (else, it's already saved).
        if ($line[1] == '') {
          $result['name']['term'][$line[0]] = $current_result['name'];
          $result['tid']['term'][$line[0]]  = $current_result['tid'];
        }
        $result['msg'] = array_merge($result['msg'], $current_result['msg']);
        if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
          break;
        }

        // 8. Import each child with main term as parent and check result.
        $parent_tid = $current_result['tid'];
        $tids = array();
        foreach ($children_names as $key => $id) {
          // No check because parent is added even if term is already imported.
          $temp_term = (object) array(
            'name'    => $id,
            'vid'     => $vid,
            'parents' => array($parent_tid),
          );
          // Update term with tid and true name if known.
          if (isset($result['name']['term'][$id])) {
            $temp_term->name = $result['name']['term'][$id];
            $temp_term->tid  = $result['tid']['term'][$id];
          }
          // Import term then store and check result.
          // Don't check tid == parent_tid because duplicates have been removed.
          $current_result = taxonomy_csv_term_import(
            $temp_term,
            $options['existing_items']);
          $result['name']['term'][$id] = $current_result['name'];
          $result['tid']['term'][$id]  = $current_result['tid'];
          $result['msg'] = array_merge($result['msg'], $current_result['msg']);
          if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
            break 2;
          }
        }
        break;

      case TAXONOMY_CSV_FORMAT_FLAT:
        foreach ($line as $term_name) {
          $term = (object) array(
            'name' => $term_name,
            'vid'  => $options['vocabulary_id'],
          );
          // Import term then store and check result.
          $current_result = taxonomy_csv_term_import($term, $options['existing_items']);
          $result['name'][] = $current_result['name'];
          $result['tid'][]  = $current_result['tid'];
          $result['msg'] = array_merge($result['msg'], $current_result['msg']);
          if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
            break;
          }
        }
        break;

      case TAXONOMY_CSV_FORMAT_TREE_STRUCTURE:
        // Same process, except when term is imported.
      case TAXONOMY_CSV_FORMAT_POLYHIERARCHY:
        // First, check if one term or partial line format is used in order to
        // virtually complete line with previous line.
        // Find first non empty item as line can be full, partial or one term.
        for ($first = 0; ($first < count($line)) && empty($line[$first]); $first++) {
        }
        // Else, line is full. As with one term import, imported items on
        // previous line can be bypassed if lines are ordered. So, look for
        // first different item.
        // This check shouldn't be done with IGNORE_ALL, currently not
        // implemented because it's useless.
        if (!$first) {
          for ($first = 0;
            ($first < count($line) - 1)
              && ($first < count($previous_items['name']))
              && ($line[$first] == $previous_items['name'][$first]);
            $first++) {
          }
        }

        // Second, remove and remember surabondant previous items for next line.
        if ($first) {
          $result['name'] = $previous_items['name'] = array_slice($previous_items['name'], 0, $first);
          $result['tid']  = $previous_items['tid']  = array_slice($previous_items['tid'], 0, $first);
          $result['msg'][] = 683; // Previous line term.

          // Set root or previous ancestor name and id.
          $parent_name = $previous_items['name'][$first - 1];
          $parent_tid  = $previous_items['tid'][$first - 1];
        }
        // Item is the first on the line: it's a root term.
        else {
          $parent_name = '';
          $parent_tid  = 0;
        }

        // Third, import each new term then store and check result.
        for ($c = $first; $c < count($line); $c++) {
          $term = (object) array(
            'name'    => $line[$c],
            'vid'     => $options['vocabulary_id'],
            'parents' => array($parent_tid),
          );

          switch ($options['import_format']) {
            case TAXONOMY_CSV_FORMAT_TREE_STRUCTURE:
              // With TAXONOMY_CSV_EXISTING_IGNORE_CREATE, parent terms (so all
              // terms but the last on this line) are always updated because
              // they are successive parents of a child.
              $current_result = ($options['existing_items'] == TAXONOMY_CSV_EXISTING_IGNORE_CREATE && ($c < count($line) - 1)) ?
                  taxonomy_csv_term_import($term, TAXONOMY_CSV_EXISTING_IGNORE_PREVIOUS, FALSE, $parent_tid) :
                  taxonomy_csv_term_import($term, $options['existing_items'], FALSE, $parent_tid);
              break;

            case TAXONOMY_CSV_FORMAT_POLYHIERARCHY:
              // Check direct duplicates: in Drupal, a term can't be its parent.
              $current_result = ($term->name == $parent_name) ?
                  taxonomy_csv_term_import($term, $options['existing_items'], FALSE, $parent_tid) :
                  taxonomy_csv_term_import($term, $options['existing_items'], FALSE, NULL);
              break;
          }
          $result['name'][] = $parent_name = $current_result['name'];
          $result['tid'][]  = $parent_tid  = $current_result['tid'];
          $result['msg'] = array_merge($result['msg'], $current_result['msg']);
          if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
            break;
          }
        }
        break;

      case TAXONOMY_CSV_FORMAT_PARENTS:
        // First, import parents using a recursive call and check result.
        if (count($line) > 1) {
          $options_temp = $options;
          $options_temp['import_format'] = TAXONOMY_CSV_FORMAT_FLAT;
          $result = taxonomy_csv_line_import(array_slice($line, 1), $options_temp);
          if (_taxonomy_csv_worst_message($result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
            break;
          }
        }

        // Second, import main term and store result.
        // Don't check tid == parent_tid because duplicates have been removed.
        $term = (object) array(
          'name'    => $line[0],
          'vid'     => $options['vocabulary_id'],
          'parents' => $result['tid'],
        );
        $current_result = taxonomy_csv_term_import($term, $options['existing_items']);
        // No check because only one term.
        // Use array_unshift in order to keep $line order of items.
        array_unshift($result['name'], $current_result['name']);
        array_unshift($result['tid'], $current_result['tid']);
        $result['msg'] = array_merge($result['msg'], $current_result['msg']);
        break;

      case TAXONOMY_CSV_FORMAT_CHILDREN:
        // First, import main term then store and check result.
        $options_temp = $options;
        $options_temp['import_format'] = TAXONOMY_CSV_FORMAT_ALONE_TERMS;
        $result = taxonomy_csv_line_import(array($line[0]), $options_temp);
        if (_taxonomy_csv_worst_message($result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
          break;
        }
        // Remember tid of main term.
        $parent_tid = $result['tid'][0];

        // Second, import children.
        foreach (array_slice($line, 1) as $value) {
          $term = (object) array(
            'name'    => $value,
            'vid'     => $options['vocabulary_id'],
            'parents' => array($parent_tid),
          );

          // Import term then store and check result.
          // Don't check tid == parent_tid because duplicates have been removed.
          $current_result = taxonomy_csv_term_import($term, $options['existing_items']);
          $result['name'][] = $current_result['name'];
          $result['tid'][]  = $current_result['tid'];
          $result['msg'] = array_merge($result['msg'], $current_result['msg']);
          if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
            break;
          }
        }
        break;

      case TAXONOMY_CSV_FORMAT_RELATIONS:
        // Each related term should exist before first column term can be
        // related to it (need of its tid). Next, process relations and
        // eventually subrelations.
        // So with a line A,B,C,D,E..., creates A, BA, CA(B), DA(BC), EA(BCD)...
        // as it's the fatest way to create one or all relations even if items
        // don't exist.
        // As use of name is only for first column, need to keep previous tids
        // matching names in $previous_relations ([name] <=> [tid]).
        $previous_relations = array();
        foreach ($line as $key => $term) {
          $term = (object) array(
            'name'      => $line[$key],
            'vid'       => $options['vocabulary_id'],
            'relations' => array_slice($previous_relations, 0, ($options['relations_create_subrelations']) ? $key : $key != 0),
          );

          // @todo Set an 'ignore all' option.
          // For second and next terms, update merge related terms in order to
          // keep previous relations, as option is only for first column term.
          $current_term_import_option = ($key == 0) ?
            $options['existing_items'] :
            TAXONOMY_CSV_EXISTING_UPDATE_MERGE;

          $current_result = taxonomy_csv_term_import($term, $current_term_import_option, $options['relations_all_vocabularies'], NULL);
          $result['name'][] = $current_result['name'];
          $result['tid'][]  = $current_result['tid'];
          $result['msg'] = array_merge($result['msg'], $current_result['msg']);
          if (_taxonomy_csv_worst_message($current_result['msg']) < TAXONOMY_CSV_PROCESS_NOTICE) {
            break;
          }

          $previous_relations[$current_result['name']] = $current_result['tid'];
        }

        // Unlike Drupal 6, relation field is linked only in one way. So, an
        // update is needed.
        $terms = $options['relations_create_subrelations'] ?
          $previous_relations :
          array($line[0] => $previous_relations[$line[0]]);

        foreach ($terms as $name => $tid) {
          // Remove relation to itself.
          $relations = $previous_relations;
          unset($relations[$name]);
          $relations = array_values($relations);

          $term = (object) array(
            'tid'       => $tid,
            'name'      => $name,
            'vid'       => $options['vocabulary_id'],
            'relations' => $relations,
          );

          $current_result = taxonomy_csv_term_import($term, TAXONOMY_CSV_EXISTING_UPDATE_MERGE, $options['relations_all_vocabularies'], NULL);
          // Terms are already memorised.
          $result['msg'] = array_merge($result['msg'], $current_result['msg']);
        }
        break;

      case TAXONOMY_CSV_FORMAT_TAXONOMY_MANAGER:
        $term = (object) array(
          'name' => $line[2],
          'vid'  => $options['vocabulary_id'],
        );
        if ($line[3]) {
          $term->description = $line[3];
        }

        // Complete with parents except root. All parents are already checked.
        $list_parents = array_slice($line, 4);
        if ($list_parents[0] != 0) {
          foreach ($list_parents as $value) {
            $term->parents[] = $previous_items['tid'][$value];
          }
        }

        // Use tid if term is already imported.
        if (isset($previous_items['tid'][$line[1]])) {
          $term->tid = $previous_items['tid'][$line[1]];
        }
        else {
          // No tid is given, so it's always a new term.
          $options['existing_items'] = TAXONOMY_CSV_EXISTING_IGNORE_CREATE;
        }

        // Import term then store result. No check because only one term.
        $current_result = taxonomy_csv_term_import($term, $options['existing_items']);
        // Keep old index (names and tids).
        $result = $previous_items;
        $result['name'][$line[1]] = $current_result['name'];
        $result['tid'][$line[1]]  = $current_result['tid'];
        $result['msg'] = $current_result['msg'];
        break;

      default:
        $result['msg'][] = 306; // Error unknown import format.
    }
  }
  else {
    $result['msg'][] = 685; // No term to process.
  }

  return $result;
}

/**
 * Update or create a term with the given name in the given vocabulary and
 * given parent.
 *
 * @param $term
 *   A term object to import. Term contains:
 *   - 'name'        => term name string,
 *   and eventually, matching options:
 *   - 'tid'         => term id,
 *   - 'vid'         => the vocabulary id where to import,
 *   - 'description' => description string,
 *   - 'weight'      => weight integer,
 *   - 'parents'     => array of first level parent tids,
 *   // Specific thesaurus fields.
 *   - 'synonyms'    => array of synonyms terms names strings,
 *   - 'relations'   => array of related tids,
 * @param $existing_items
 *   (Optional) Type of import on existing terms. Default to ignore and create.
 * @param $all_vocabularies
 *   (Optional) Boolean. Search in all vocabularies or only in $term->vid
 *   vocabulary (default), which need to be set. Used with relations import.
 * @param $parent_tid
 *   (Optional) The direct parent term id where to restrict search.
 *   Used for structure import. Default to NULL (no parent restriction).
 *
 * @return array
 *    'name' => term name,
 *    'tid'  => term id,
 *    'msg'  => messages array.
 */
function taxonomy_csv_term_import($term, $existing_items = TAXONOMY_CSV_EXISTING_IGNORE_CREATE, $all_vocabularies = FALSE, $parent_tid = NULL) {
  $messages = array();

  if ($term) {
    switch ($existing_items) {
      case TAXONOMY_CSV_EXISTING_UPDATE:
        // When no difference between update/merge and update/replace.
      case TAXONOMY_CSV_EXISTING_UPDATE_MERGE:
        $existing_term = taxonomy_csv_term_find($term, $all_vocabularies, $parent_tid);
        if ($existing_term) {
          // Get name is not case sensitive and a name can be updated.
          if (isset($term->name)) {
            $existing_term->name = $term->name;
          }
          if (isset($term->vid)) {
            $existing_term->vid = $term->vid;
          }
          if (isset($term->parents)) {
            $existing_term->parents = array_unique(array_merge($existing_term->parents, $term->parents));
          }
          if (isset($term->description)) {
            $i = trim($existing_term->description);
            $existing_term->description = (($i == '') || ($i == $term->description)) ?
              $term->description :
              $i . "\n" . $term->description;
          }
          // Weight is always replaced as it is a simple number.
          if (isset($term->weight)) {
            $existing_term->weight = $term->weight;
          }
          if (isset($term->synonyms)) {
            $existing_term->synonyms = array_unique(array_merge($existing_term->synonyms, $term->synonyms));
          }
          if (isset($term->relations)) {
            $existing_term->relations = array_unique(array_merge($existing_term->relations, $term->relations));
          }
          $term = $existing_term;
        }
        break;

      case TAXONOMY_CSV_EXISTING_IGNORE_PREVIOUS:
        // Doesn't ignore, but use previous parents.
      case TAXONOMY_CSV_EXISTING_UPDATE_REPLACE:
        $existing_term = taxonomy_csv_term_find($term, $all_vocabularies, $parent_tid);
        if ($existing_term) {
          foreach (array(
              'name',
              'vid',
              'description',
              'weight',
              'parents',
              'synonyms',
              'relations',
            ) as $key) {
            if (array_key_exists($key, $term)) {
              $existing_term->$key = $term->$key;
            }
          }
          $term = $existing_term;
        }
        break;

      case TAXONOMY_CSV_EXISTING_IGNORE:
        // When no difference between ignore/create and ignore/all.
      case TAXONOMY_CSV_EXISTING_IGNORE_CREATE:
        break;

      case TAXONOMY_CSV_EXISTING_IGNORE_ALL:
        // Ignore even existing terms in additional columns.
        // @todo IGNORE_ALL
        break;

      case TAXONOMY_CSV_EXISTING_IGNORE_QUICK:
        // Don't check existing terms. That's very similar to ignore all.
        break;
    }

    // Save regularly formatted term.
    // Return either SAVED_NEW, SAVED_UPDATED or FALSE (no change).
    $result = _taxonomy_csv_term_save($term);

    $messages[] = ($result == SAVED_NEW) ? 691 : 692; // Saved or updated.
  }
  else {
    $messages[] = 391; // Error: not a term.
  }

  return array(
    'name' => $term->name,
    'tid'  => $term->tid,
    'msg'  => $messages,
  );
}
