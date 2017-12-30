This module adds Api Key text field to /admin/config/system/site-information.
Also it changes the Save configuration button's text to Update Configuration.
Api Key is used for checking the acces of code_test.get_node route.


This module also provides a route that responds with a JSON representation of a
given node with the content type "page" only if the previously submitted API Key
and a node id (nid) of an appropriate node are present, otherwise it will
respond with "access denied".