# ReactJS server-side renderer

What does this bundle do:
1. Allow reactjs usage with symfony twig
2. Allow server side reactjs (polymorphic) rendering (OPTIONAL)

Requirements:

1. Node.js >= v0.12
2. NPM
3. Grunt
4. Webpack

This bundle allows symfony to utilize server side rendering (for SEO) for [reactjs](http://facebook.github.io/react/).
For this to work, a nodejs express server is used to return a string representing the static HTML of the initial reactjs page. This is achieved by sending an HTTP request to the nodejs express server.

Once the page is loaded into the browser, the client side reactjs kicks in and the javascript magic begins using the available [webpack](http://webpack.github.io/) bundled reactjs files.

This bundle also a Twig extension to allow react to be used directly with your twig templates. You need not enable the server side renderer to make use of this feature.

### React Tags

There are currently two flavors of custom twig tags for ReactJS, one for with props and another for without.

#### With Props

Syntax:
```
{{{'prop1' : 'value1', ...}|react('component_name')}}
```
Ex:
```
{{{'name' : 'Kylo Ren'}|react('hello')}}
```
*Note that the entire statement is wrapped around two curly braces, the props with a pair of curly braces. A pipe ('|') is also present between the props and the react function containing the component name as the parameter.*

#### Without Props

Syntax:
```
react('component_name')
```
Ex:
```
{{react('countdown')}}
```
*Syntax is the same as the one before but without the props and the pipe character.*


## ReactJS in Development Environments

Most of the ReactJS Components that we will be creating would use the JSX sub-language to make the development process painless and fast. Unfortunately, no browser in the world understands what JSX is so we have to transpile (translate + compile) it first to regular Javascript before we can see the component in action. This is where [webpack](http://webpack.github.io/) comes in.



# Installation

A. Add the react bundle in your AppKernel.php file    

    ```
        $bundles = array(
            //...
            new React\Bundle\ServerSideRendererBundle\ReactServerSideRendererBundle(),
        );
    ```    
    
B. Configure the bundle by adding the following into `app/config/config.yml` of your Symfony2 application.

```
react_server_side_renderer:
    renderer:
        render_server: "http://localhost:3000"
    twig_extension:
        src_path: "./reactjs/src/"
```

- renderer.render_server: this is the HTTP URI where the nodejs server resides
- twig_extension.src_path: this is the location of the reactjs source files 

C. Navigate to the `Nodejs` directory and run `npm install` to install the bundle specific dependencies

D. Add a package.json file to the root of your application and add the following dependencies:

```
 "dependencies": {
   ...
   "grunt": "~0.4.5", 
   "grunt-webpack": "^1.0.8",
   "webpack": "^1.10.1"
   "babel-core": "^5.6.18",
   "babel-loader": "^5.3.1",
   "react": "^0.13.3"
 }
```

Again run, `npm install` but this time at the root of the application to install the application node dependencies  

E. Create a `Gruntfile.js` at the root of the application and add the following webpack block:

```
var path = require('path');
var pagesBase = path.resolve('./web/assets/reactjs/maps');
var webpack = require('webpack');

module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        webpack: {
            reactComponents: {
                entry: grunt.file.expand({cwd: pagesBase}, '*Map.js').reduce(
                        function(map, page, index, array) {
                            map[path.basename(page)] = path.join(pagesBase, page);
                            if(index == array.length-1){
                                map['vendor'] = ['react'];
                            }
                            return map;
                        }, {}
                    ),
                output: {
                    path: './web/assets/reactjs/build',
                    filename: '[name]' // Template based on keys in entry above
                },
                module: {
                    loaders: [
                        {test: /\.js?$/, exclude: /node_modules|bower_components/, loader: 'babel-loader'}
                    ]
                },
                plugins: [
                    new webpack.optimize.CommonsChunkPlugin(/* chunkName= */'vendor', /* filename= */'vendor.js')
                ],
                failOnError: false,
                watch: grunt.option('watch'),
                keepalive: grunt.option('watch'),
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-webpack');
    grunt.registerTask('default', ['webpack']);
};

```

Read more about creating a Gruntfile here: http://gruntjs.com/sample-gruntfile

F. Move react templating files from the bundle to the symfony web directory:

- 'Resource/public/js/reactRender.js' -> '[symfony2/assets/directory]/js/reactRender.js'
- 'Resource/public/js/mapTemplate.js' -> '[symfony2/assets/directory]/reactjs/maps/mapTemplate.js'



# Usage

## Using React with TWIG

![what are you looking at?](http://i.imgur.com/XjDNc6h.png)

All you have to do to reference reactjs in your pages is to:

- Code your reactjs components and put them in your reactjs source directory, for example `web/assets/reactjs/src`
- Create a map file in and add it to your reactjs maps directory, for example: `web/assets/reactjs/map`. 
  There is usually one map file per page. This contains the reactjs components used in a particular page.

See the example map file and react component files in https://github.com/nelsoft-easyshop/react-serverside-renderer/tree/master/Resources/public/example

- Build using grunt-webpack by running `grunt`
  Note that everytime a react component is changed, you will have to rerun grunt. You can skip this by simply running `grunt --watch` instead
- Add your react twig tags into your view file
- Reference the bundled map file in your twig templates as well as the `reactRender.js` file. The reactRender file simply attaches your react client side logic to the server-side-generated react html.

```
    <script src="{{ asset('reactjs/build/somepageMap.js') }}"></script>
    <script src="{{ asset('js/src/reactRender.js') }}"></script>

```


Note that if the nodejs server is not running, then you won't have server side rendering enabled but your app will still work. In other words, your app won't be indexable but you can use it as it is. This is useful when your working on your dev environment. 


## Server Side Renderer Usage

Navigate to the assets directory of your application and then from here execute `server.js` in  the bundle
For example:

```
cd ../../../../web/assets
node ../../vendor/yilinker/reactjs-serverside-renderer/Nodejs/server.js
```

Note that you don't have to start the SSR server to make the react integration work. You only need it if you want server side rendering, which is usually just in the production environment.


### Pro-tip:

You can make the execution step faster by creating an npm run-script for server.js:

A. At the package.json in the root of the application, add the following script commands:

```
  "scripts": {
      "react-start": "cd vendor/yilinker/reactjs-serverside-renderer/Nodejs && npm start"
  },
```

B. At the package.json in `yilinker/reactjs-serverside-renderer/Nodejs/`, add a *start* script that performs the appropriate directory change command and the node initiation command. Note, you will most likely have to modify the commands a bit depending on how your folders are structured.

```
  "scripts": {
    "start": "cd ../../../../web/assets && node ../../vendor/yilinker/reactjs-serverside-renderer/Nodejs/server.js",
  },
```

C. You can then simply run `npm run-script react-start` at the root of your symfony application
 






