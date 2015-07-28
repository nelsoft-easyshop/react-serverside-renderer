window.renderReact = function(){
    var forEach = Array.prototype.forEach;
    var nodes = document.querySelectorAll('[react-component-name]');
    var React = window.ReactTemplates['react'];
    var csrfToken = document.getElementsByTagName('meta')['csrf-token'].getAttribute('content');

    forEach.call(nodes, function(node){
        var name = node.getAttribute('react-component-name')
        var props = JSON.parse(node.getAttribute('react-props'));
        if (props) {
            props['csrfToken'] = csrfToken;
        }
        if(typeof window.ReactTemplates[name] == 'undefined'){
            throw 'Component ' + name + 'does not exist!';
        }

        var component = window.ReactTemplates[name];
        React.render(React.createElement(component, props), node);
    });
};

window.renderReact();

