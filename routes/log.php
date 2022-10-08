const { Router } = require('express');

const Response = require('../model/response');

const DB = require('../data/db');
const LogData = require('../data/logData');

const { verificarAdmin } = require('../middleware/authjwt');

const router = Router();

const routerBase = '/api/log';
const xl = require('excel4node');
const path = require('path');
const config = require('../config');

const db = new DB(config.DB.LOG_TABLE);

router.get(`${routerBase}/excel`, (req, res) => {
    const logData = new LogData(db);

    logData.getAll()
        .then(result => {
            if (result.status === 200) {
                const wb = new xl.Workbook();
                const ws = wb.addWorksheet('Log');

                ws.cell(1, 1).string('Id');
                ws.cell(1, 2).string('Usuario');
                ws.cell(1, 3).string('Id usuario');
                ws.cell(1, 4).string('Acción');
                ws.cell(1, 5).string('Tabla afectada');
                ws.cell(1, 6).string('Datos');
                ws.cell(1, 7).string('Fecha');


                result.data.map((log, key) => {
                    const create_time = new Date(log.create_time);

                    const numberRow = key + 2;

                    ws.cell(numberRow, 1).number(log.id);
                    ws.cell(numberRow, 2).string(log.usuario);
                    ws.cell(numberRow, 3).number(log.id_usuario);
                    ws.cell(numberRow, 4).string(log.accion);
                    ws.cell(numberRow, 5).string(log.tabla);
                    ws.cell(numberRow, 6).string(log.datos);
                    ws.cell(numberRow, 7).date(create_time);
                })

                const pathExcel = path.join('excel', 'log.xlsx');

                wb.write(pathExcel, (err) => {
                    if (err) {
                        console.log(err)
                        return;
                    }
                    res.download(pathExcel);
                });
                return;
            }
            Response.sendResponse(result, res);
        })
});

module.exports = router;