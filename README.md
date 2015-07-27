# ReactJS server-side renderer

This bundle allows symfony to utilize server side rendering (for SEO) for [reactjs](http://facebook.github.io/react/).
For this to work, a nodejs express server is used to return a string representing the static HTML of the initial reactjs page. This is achieved by sending an HTTP request to the nodejs express server.

Once the page is loaded into the browser, the client side reactjs kicks in and the javascript magic begins using the available [webpack](http://webpack.github.io/) bundled reactjs files.

This bundle adds a Twig extension to load the reactjs properties. 

# Installation

Configure the bundle by adding the following into `app/config/config.yml` of your Symfony2 application.

```
react_server_side_renderer:
    renderer:
        render_server: "http://localhost:3000"
    twig_extension:
        src_path: "./reactjs/src/"
```

- renderer.render_server: this is the HTTP URI where the nodejs server resides
- twig_extension.src_path: this is the location of the reactjs source files 

### React Tags

There are currently two flavors of custom twig tags for ReactJS, one for with props and another for without.

With Props

Syntax:

{{{'prop1' : 'value1', ...}|react('component_name')}}

Ex:

{{{'name' : 'Kylo Ren'}|react('hello')}}

Note that the entire statement is wrapped around two curly braces, the props with a pair of curly braces. A pipe ('|') is also present between the props and the react function containing the component name as the parameter.

