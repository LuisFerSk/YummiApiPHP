const { Router } = require('express');

const Response = require('../model/response');

const DB = require('../data/db');
const SubsectorData = require('../data/subsectorData');

const { verificarUser } = require('../middleware/authjwt');
const config = require('../config');

const router = Router();

const routerBase = '/api/subsector';

const db = new DB(config.DB.SUBSECTORE_TABLE);

router.get(routerBase, verificarUser, (req, res) => {
    const subsectorData = new SubsectorData(db);

    subsectorData.getAll()
        .then(result => {
            Response.sendResponse(result, res);
        })
})

module.exports = router;