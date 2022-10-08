const { Router } = require('express');

const Periferico = require('../model/periferico');
const Response = require('../model/response');

const DB = require('../data/db');
const PerifericoData = require('../data/perifericoData');

const { verificarUser, verificarAdmin, decodeToken } = require('../middleware/authjwt');
const config = require('../config');
const xl = require('excel4node');
const path = require('path');
const LogData = require('../data/logData');

const router = Router();
const routerBase = '/api/periferico';

const db = new DB(config.DB.PERIFERICO_TABLE);
const logTable = new DB(config.DB.LOG_TABLE);

function generateExcelPerifericos(props) {
    const { perifericos, pageName } = props;

    const workbook = new xl.Workbook();
    const worksheet = workbook.addWorksheet(pageName);

    worksheet.cell(1, 1).string('Id');
    worksheet.cell(1, 2).string('Tipo de dispositivo');
    worksheet.cell(1, 3).string('Referencia');
    worksheet.cell(1, 4).string('Número serial');
    worksheet.cell(1, 5).string('Estado');
    worksheet.cell(1, 6).string('Observaciones');
    worksheet.cell(1, 7).string('Fecha de creación');
    worksheet.cell(1, 8).string('Ultima actualización');

    perifericos.map((periferico, key) => {
        const create_time = new Date(periferico.create_time);

        const numberRow = key + 2;

        if (periferico.update_time) {
            const update_time = new Date(periferico.update_time);
            worksheet.cell(numberRow, 8).date(update_time);
        }

        if (periferico.observaciones) {
            worksheet.cell(numberRow, 6).string(periferico.observaciones);
        }

        worksheet.cell(numberRow, 1).number(periferico.id);
        worksheet.cell(numberRow, 2).string(periferico.tipo_dispositivo);
        worksheet.cell(numberRow, 3).string(periferico.referenciaPeriferico);
        worksheet.cell(numberRow, 4).string(periferico.numeroSerial);
        worksheet.cell(numberRow, 5).string(periferico.estado);
        worksheet.cell(numberRow, 7).date(create_time);
    })

    return workbook;
}

router.get(`${routerBase}/excel/all`, verificarUser, async (req, res) => {
    const perifericoData = new PerifericoData(db);

    perifericoData.getAllForExcel()
        .then(result => {
            if (result.status === 200) {
                const propsGenerateExcel = {
                    perifericos: result.data,
                    pageName: 'Periféricos'
                }

                const workbook = generateExcelPerifericos(propsGenerateExcel)

                const pathExcel = path.join('excel', 'periféricos.xlsx');

                workbook.write(pathExcel, (err) => {
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
router.get(`${routerBase}/excel/by-tipo-dispositivo/:id`, verificarUser, async (req, res) => {
    const perifericoData = new PerifericoData(db);

    const { id } = req.params;

    perifericoData.getAllByTipo(id)
        .then(result => {
            if (result.status === 200) {
                const propsGenerateExcel = {
                    perifericos: result.data,
                    pageName: 'Periféricos'
                }

                const workbook = generateExcelPerifericos(propsGenerateExcel)

                const pathExcel = path.join('excel', `periféricos-tipo-dispositivo-${id}.xlsx`);

                workbook.write(pathExcel, (err) => {
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
router.get(routerBase, verificarUser, (req, res) => {
    const perifericoData = new PerifericoData(db);

    perifericoData.getAll()
        .then(result => {
            Response.sendResponse(result, res);
        })
});
router.get(`${routerBase}/count`, verificarUser, (req, res) => {
    const perifericoData = new PerifericoData(db);

    perifericoData.count()
        .then(result => {
            Response.sendResponse(result, res);
        })
})
router.post(routerBase, verificarUser, (req, res) => {
    const periferico = new Periferico();
    const perifericoData = new PerifericoData(db, periferico);

    const {
        tipo_dispositivo,
        referenciaPeriferico,
        numeroSerial,
        estado,
        observaciones
    } = req.body;

    const dataToInsert = {
        tipo_dispositivo,
        referenciaPeriferico,
        numeroSerial,
        estado,
        observaciones
    }

    perifericoData.insert(dataToInsert)
        .then(result => {
            const resultDecodeToken = decodeToken(req)

            const dataForLog = {
                usuario: resultDecodeToken.data.username,
                id_usuario: resultDecodeToken.data.id,
                accion: 'Registrar',
                tabla: config.DB.PERIFERICO_TABLE,
                datos: JSON.stringify({ id: result.data.id, ...dataToInsert })
            }
            const logData = new LogData(logTable);

            logData.insert(dataForLog).then(() => {
                Response.sendResponse(result, res);
            })
        })
        .catch(err => {
            console.log(err)
            Response.sendResponse(err, res)
        })
});
router.put(`${routerBase}/:id`, verificarUser, (req, res) => {
    const periferico = new Periferico();
    const perifericoData = new PerifericoData(db, periferico);

    const { id } = req.params;

    const {
        tipo_dispositivo,
        referenciaPeriferico,
        numeroSerial,
        estado,
        observaciones
    } = req.body;

    const dataToUpdate = {
        tipo_dispositivo,
        referenciaPeriferico,
        numeroSerial,
        estado,
        observaciones
    }

    perifericoData.update(parseInt(id), dataToUpdate)
        .then(result => {
            const resultDecodeToken = decodeToken(req)

            const dataForLog = {
                usuario: resultDecodeToken.data.username,
                id_usuario: resultDecodeToken.data.id,
                accion: 'Actualizar',
                tabla: config.DB.PERIFERICO_TABLE,
                datos: JSON.stringify({ id: result.data.id, ...dataToUpdate })
            }
            const logData = new LogData(logTable);

            logData.insert(dataForLog).then(() => {
                Response.sendResponse(result, res);
            })
        })
        .catch(err => {
            Response.sendResponse(err, res)
        })
})
router.delete(`${routerBase}/:id`, verificarAdmin, (req, res) => {
    const periferico = new Periferico();
    const perifericoData = new PerifericoData(db, periferico);

    const { id } = req.params;

    perifericoData.delete(parseInt(id))
        .then(result => {
            const resultDecodeToken = decodeToken(req)

            const dataForLog = {
                usuario: resultDecodeToken.data.username,
                id_usuario: resultDecodeToken.data.id,
                accion: 'Eliminar',
                tabla: config.DB.PERIFERICO_TABLE, usuario: resultDecodeToken.data.username,
                id_usuario: resultDecodeToken.data.id,
                datos: JSON.stringify({ id })
            }
            const logData = new LogData(logTable);

            logData.insert(dataForLog).then(() => {
                Response.sendResponse(result, res);
            })
        })
        .catch(err => {
            Response.sendResponse(err, res)
        })
})

module.exports = router;