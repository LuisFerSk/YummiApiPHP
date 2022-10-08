const { Router } = require('express');

const Response = require('../model/response');

const DB = require('../data/db');
const SectorialData = require('../data/sectorialData');

const { verificarUser } = require('../middleware/authjwt');
const config = require('../config');

const router = Router();

const routerBase = '/api/sectorial';

const db = new DB(config.DB.SECTORIALE_TABLE);

router.get(routerBase, verificarUser, (req, res) => {
    const sectorialData = new SectorialData(db);

    sectorialData.getAll()
        .then(result => {
            Response.sendResponse(result, res);
        })
})

module.exports = router;