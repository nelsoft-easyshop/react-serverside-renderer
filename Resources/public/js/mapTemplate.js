var context = typeof window === 'object' ? window : global;

context.ReactTemplates = {
   'react': require('react')
};

module.exports = context;
