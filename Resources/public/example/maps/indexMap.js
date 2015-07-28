// Require map template
var context = require('./mapTemplate');

// Add react components used in indext.html.twig
context.ReactTemplates['countdown'] = require('./../src/countdown');
context.ReactTemplates['hello'] = require('./../src/hello');

