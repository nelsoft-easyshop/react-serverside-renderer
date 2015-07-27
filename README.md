# ReactJS server-side renderer

Requirements:

1. Node.js >= v0.12
2. NPM
3. Grunt
4. Webpack

This bundle allows symfony to utilize server side rendering (for SEO) for [reactjs](http://facebook.github.io/react/).
For this to work, a nodejs express server is used to return a string representing the static HTML of the initial reactjs page. This is achieved by sending an HTTP request to the nodejs express server.

Once the page is loaded into the browser, the client side reactjs kicks in and the javascript magic begins using the available [webpack](http://webpack.github.io/) bundled reactjs files.

This bundle adds a Twig extension to load the reactjs properties. 

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

A. Configure the bundle by adding the following into `app/config/config.yml` of your Symfony2 application.

```
react_server_side_renderer:
    renderer:
        render_server: "http://localhost:3000"
    twig_extension:
        src_path: "./reactjs/src/"
```

- renderer.render_server: this is the HTTP URI where the nodejs server resides
- twig_extension.src_path: this is the location of the reactjs source files 

B. Navigate to the `Nodejs` directory and run `npm install`
C. Add a package.json file to the root of your application and add the following dependencies:

```
 "dependencies": {
   ...
   "grunt": "~0.4.5", 
   "grunt-webpack": "^1.0.8",
   "webpack": "^1.10.1"
   "babel-core": "^5.6.18",
   "babel-loader": "^5.3.1"
 }
```

D. Create a `Gruntfile.js` at the root of the application and add the following webpack block:

```
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

```

Don't forget to add the webpack task to grunt using

```
    grunt.loadNpmTasks('grunt-webpack');
    grunt.registerTask('default', ['webpack']);
```

Read more about creating a Gruntfile here: http://gruntjs.com/sample-gruntfile



Then run the nodejs server by going to the root of the application:
```
npm run-script react-start
```






