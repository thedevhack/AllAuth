const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql2');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');


// db connection
const pool = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '12356',
  database: 'db1'
});


let app = express();
let port = 3000;

app.use(bodyParser.json());



app.post("/register", async(request, res) => {
    try {
        const hashedPassword = await bcrypt.hash(request.body.password, 10);
        pool.query('INSERT INTO base_user(email, password) VALUES (?, ?)',
        [request.body.email, hashedPassword],
        (err, result) => {
            if (err) {
                console.error(err);
                return res.status(502).send();
            }
            res.status(201).send();
        });

    } catch (err){
        console.log(err);
        res.status(500).send();
    }
});

app.post('/login', async (request, res) => {
    pool.query('SELECT * FROM base_user WHERE email = ?',[request.body.email], async (error, results) => {
        if (error) {
            console.error(err);
            return res.status(502).send();
        }
        const user = results[0];
        if (!user) {
            return res.status(400).send('user not with email')
        }
        try {
        const accessTokenSecret = process.env.ACCESS_TOKEN_SECRET || 'lknnighshs@$^#%($difs343@@$*%)_';
        if (await bcrypt.compare(request.body.password, user.password)) {
          const accessToken = jwt.sign(user, accessTokenSecret);
          res.json({ accessToken: accessToken ,"status":"loggedin"});
        } else {
          res.status(401).send('Incorrect password');
        }
      } catch (error){
        console.log(error);
        res.status(500).send();
      }
    });
})




app.listen(port, () => {
  console.log(`Server is running`);
});