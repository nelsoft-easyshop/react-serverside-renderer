# ReactJS server-side renderer

This bundle allows symfony to utilize server side rendering (for SEO) for [reactjs](http://facebook.github.io/react/).
For SSR, a nodejs express server is used to return a string representing the static HTML of the initial reactjs page.
The Symfony application will send a request to this nodejs server.

Once the page is loaded into the browser, the client side reactjs kicks in and the javascript magic begins using  
[webpack](http://webpack.github.io/)  bundled reactjs files.


