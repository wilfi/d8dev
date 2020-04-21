CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * How it works
 * Maintainers


INTRODUCTION
------------

Views Json Source is an extension of views module to work with external JSON data.
Any JSON API can be configured, to render the data from the JSON through views.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/views_json_source

 * To submit bug reports and feature suggestions, or to track changes:
   https://www.drupal.org/project/issues/search/views_json_source


REQUIREMENTS
------------

No special requirements.


INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module.
   See: https://www.drupal.org/node/895232 for further information.


CONFIGURATION
-------------

No configuration is needed.


HOW IT WORKS
------------

When creating a view choose "Json" on "Show" field.
Then when editing the view, In "Advanced" section, click "Settings" on "Query settings",
choose the source of the Json data, define the row apath and you're done.
Now just add one or more fields and set the key value of the field you want to show.
Add any filters or sort criteria as required.
Make use of Contextual filters as required.

Example #1:
A file called nodes.json that is hosted on www.example.com with the
following structure:
{
  "data": {
    "nodes": [
      {
        "nid": 1,
        "title": "Sample Node One",
        "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
      },
      {
        "nid": 2,
        "title": "Sample Node Two",
        "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
      }
    ]
  }
}

Query settings:
  Json File: http://www.example.com/nodes.json
  Row Apath: nodes


Example #2:
A file called sample.json that is hosted on www.example.com with the
following structure:
{
  "data": {
    "nodes": {
      "contents": [
        {
          "id": 1,
          "title": "Sample One",
          "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
        },
        {
          "id": 2,
          "title": "Sample Two",
          "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
        }
      ]
    },
    "taxonomy": {
      "contents": [
        {
          "id": 1,
          "title": "Category One",
          "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
        },
        {
          "id": 2,
          "title": "Category Two",
          "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
        }
      ]
    }
  }
}

Query settings:
  Json File: http://www.example.com/sample.json
  Row Apath: %/contents
Contextual Filter:
  Add a contextual filter of type "Apath Replacement".
Sample views url: http://<domain>/<views url>/nodes or http://<domain>/<views url>/taxonomy


MAINTAINERS
-----------

Current maintainers:
 * Pradeep Venugopal (venugopp) - https://www.drupal.org/u/venugopp
