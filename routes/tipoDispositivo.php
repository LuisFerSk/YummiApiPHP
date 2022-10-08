const { Router } = require('express');

const Response = require('../model/response');

const DB = require('../data/db');
const TipoDispositivoData = require('../data/tipoDispositivoData');

const { verificarUser } = require('../middleware/authjwt');
const config = require('../config');

const router = Router();

const routerBase = '/api/tipo-dispositivo';

const db = new DB(config.DB.TIPO_DISPOSITIVO_TABLE);

router.get(routerBase, verificarUser, (req, res) => {
    const tipoDispositivoData = new TipoDispositivoData(db);

    tipoDispositivoData.getAll()
        .then(result => {
            Response.sendResponse(result, res);
        })
})

module.exports = router;