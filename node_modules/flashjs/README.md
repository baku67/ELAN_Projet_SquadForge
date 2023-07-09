# flashjs

A simple flash middleware for expressjs

## Usage

```bash
npm install flashjs
```

```js
var express = require('express')
var app = express()

app.use(require('cookie-session')({
  secret: 'secret'
})) // or other session middleware exposing res.flash functionality

app.use(require('flash'))
```

```js
app.post('/', function(req, res, next) {

  updateEmailAddress(function(err) {

    if (err) {
      return next(err)
    }

    // update process

    res.flash('success', {
      email: 'a@a'
    })

    res.redirect('back')

  })
})

app.use(function(err, req, res, next) {

  if (config.dev) {
    console.error(err.stack)
  }

  res.flash(err)
  res.redirect('back')

})
```

```jade
if flash.success
  .alert.alert-success
    strong Your email address #{flash.success.email} was updated successfully. 
```
