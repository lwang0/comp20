const csv = require('csv-parser')
const fs = require('fs')
const express = require('express')
const bodyParser = require('body-parser')
const MongoClient = require('mongodb').MongoClient

const app = express()

app.listen(process.env.PORT || 3000, function() {
    console.log('server is running')
})
app.use(bodyParser.urlencoded({ extended: true }))

var url = "mongodb+srv://me:whydoesthisneedapassword@comp20.kvecf.mongodb.net/companies?retryWrites=true&w=majority"

MongoClient.connect(url, { useUnifiedTopology: true }, function(err, client) {
    if (err) {
        console.log("Connection err: " + err)
        return
    }

    console.log("Connected!")

    const db = client.db("companies")
    const companies = db.collection("companies")

    app.get('/index.html', (req, res) => {
        res.sendFile(__dirname + '/index.html')
    })

    fs.createReadStream('companies.csv')
    .pipe(csv())
    .on('data', (row) => {
        console.log(row)
        // companies.insertOne(row)
    })

    .on('end', () => {
        console.log('CSV file successfully processed')
    })

    app.post('/result.html', (req, res) => {
        var query = req.body

        if (!query.ticker && !query.company) {
            return false
        }
        else if (query.ticker && !query.company) {
            query = { Ticker : query.ticker }
        }
        else if (!query.ticker && query.company) {
            query = { Company : query.company }
        }
        console.log(query)

        companies.find(query).toArray(function(err, result) {
            if (err) console.log("Query err: " + err)
            console.log(result)

            var html = "<!DOCTYPE html>" +
                       "<html><head><title>Query Results</title></head><body><h1>Here are the results of your query</h1>"

            result.forEach((arr, i) => {
                html += "<div>Ticker: " + arr["Ticker"] + ", Company: " + arr["Company"] + "</div><br>"
            })

            html += "</body></html>"
            res.end(html)
        })
    })
})
