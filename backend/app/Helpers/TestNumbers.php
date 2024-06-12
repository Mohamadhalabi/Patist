<?php

$isTest = false;
isset($urgent) ?: $urgent = false;
if($urgent == true){
    $number .= 'URG';
}
// Valid
if ($number == "EPTST0001B1") {
    $data = json_decode('{"number":"EPTST0001B1","response":{"priorities":[{"documentNumber":"US19830479766","date":"1983\/03\/28"}],"bibliographicData":{"publicationReference":{"country":"EP","docNumber":"0123456","kind":"B1","date":"1990-12-12"},"inventionTitle":"A COMBINED INTRAFRAME AND INTERFRAME TRANSFORM CODING METHOD","applicant":"COMPRESSION LABS, INC","inventor":"CHEN, WEN-HSIUNG,","publicationDate":"12-12-1990","status":"valid"}},"fee":{"officialFee":[{"id":1,"official_fee_id":1,"amount":"4640.00","currency":"TRY","year":2023,"created_at":"2022-10-25T12:39:58.000000Z","updated_at":"2022-10-25T12:39:58.000000Z","name":"EP validation official fee"}],"translation100keywordFee":"15","translationFee":{"fullTextCount":9293,"amount":"1,393.95","currency":"EUR"},"serviceFee":[{"id":1,"item":"EP validation service fee","slug":"ep-validation","amount":"185","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"}],"currency":{"eur":"20.0782"},"status":true}}');
    $isTest = true;
}
// Very Close To Expire
if ($number == "EPTST0002B1") {
    $data = json_decode('{"number":"EP0123456B1","response":{"bibliographicData":{"publicationReference":{"country":"EP","docNumber":"0123456","kind":"B1","date":"1990-12-12"},"inventionTitle":"A COMBINED INTRAFRAME AND INTERFRAME TRANSFORM CODING METHOD","applicant":"COMPRESSION LABS, INC","inventor":"CHEN, WEN-HSIUNG,","publicationDate":"12-12-1990","status":"very-close-to-expire"}},"fee":{"officialFee":[{"id":1,"official_fee_id":1,"amount":"4640.00","currency":"TRY","year":2023,"created_at":"2022-10-25T12:39:58.000000Z","updated_at":"2022-10-25T12:39:58.000000Z","name":"EP validation official fee"}],"translation100keywordFee":"15","translationFee":{"fullTextCount":9293,"amount":"1,393.95","currency":"EUR"},"serviceFee":[{"id":1,"item":"EP validation service fee","slug":"ep-validation","amount":"185","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"}],"currency":{"eur":"20.0782"},"status":true}}');
    $isTest = true;
}
// Close To Expire
if ($number == "EPTST0003B1") {
    $data = json_decode('{"number":"EPTST0003B1","response":{"priorities":[{"documentNumber":"US19830479766","date":"1983\/03\/28"}],"bibliographicData":{"publicationReference":{"country":"EP","docNumber":"0123456","kind":"B1","date":"1990-12-12"},"inventionTitle":"A COMBINED INTRAFRAME AND INTERFRAME TRANSFORM CODING METHOD","applicant":"COMPRESSION LABS, INC","inventor":"CHEN, WEN-HSIUNG,","publicationDate":"12-12-1990","status":"close-to-expire"}},"fee":{"officialFee":[{"id":1,"official_fee_id":1,"amount":"4640.00","currency":"TRY","year":2023,"created_at":"2022-10-25T12:39:58.000000Z","updated_at":"2022-10-25T12:39:58.000000Z","name":"EP validation official fee"},{"id":25,"official_fee_id":25,"amount":"5040.00","currency":"TRY","year":2023,"created_at":null,"updated_at":null,"name":"Extension of 3 months official fees"}],"serviceFee":[{"id":6,"item":"EP validation additional time fee","slug":"ep-additional-time-fee","amount":"50","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"},{"id":1,"item":"EP validation service fee","slug":"ep-validation","amount":"185","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"}],"translation100keywordFee":"15","translationFee":{"fullTextCount":9293,"amount":"1,393.95","currency":"EUR"},"currency":{"eur":"20.0782"},"status":true}}');
    $isTest = true;
}
// Very Close To Expire - warning : true
if ($number == "EPTST1002B1") {
    $data = json_decode('{"number":"EP0123456B1","response":{"warning": {
    "expiration": {
        "status": true,
        "expirationDate": "10-03-2023"
    },
    "quoteChange": {
        "status": false,
        "quoteChangeDate": null
    }
}
,"expirationDate":"23-03-2023","bibliographicData":{"publicationReference":{"country":"EP","docNumber":"0123456","kind":"B1","date":"1990-12-12"},"inventionTitle":"A COMBINED INTRAFRAME AND INTERFRAME TRANSFORM CODING METHOD","applicant":"COMPRESSION LABS, INC","inventor":"CHEN, WEN-HSIUNG,","publicationDate":"12-12-1990","status":"very-close-to-expire"}},"fee":{"officialFee":[{"id":1,"official_fee_id":1,"amount":"4640.00","currency":"TRY","year":2023,"created_at":"2022-10-25T12:39:58.000000Z","updated_at":"2022-10-25T12:39:58.000000Z","name":"EP validation official fee"}],"translation100keywordFee":"15","translationFee":{"fullTextCount":9293,"amount":"1,393.95","currency":"EUR"},"serviceFee":[{"id":1,"item":"EP validation service fee","slug":"ep-validation","amount":"185","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"}],"currency":{"eur":"20.0782"},"status":true}}');
    $isTest = true;
}
// Close To Expire - warning : true
if ($number == "EPTST1003B1") {
    $data = json_decode('{"number":"EPTST0003B1","response":{"priorities":[{"documentNumber":"US19830479766","date":"1983\/03\/28"}],"warning": {
    "expiration": {
        "status": false,
        "expirationDate": null
    },
    "quoteChange": {
        "status": true,
        "quoteChangeDate": "10-03-2023"
    }
}
,"expirationDate":"23-03-2023","bibliographicData":{"publicationReference":{"country":"EP","docNumber":"0123456","kind":"B1","date":"1990-12-12"},"inventionTitle":"A COMBINED INTRAFRAME AND INTERFRAME TRANSFORM CODING METHOD","applicant":"COMPRESSION LABS, INC","inventor":"CHEN, WEN-HSIUNG,","publicationDate":"12-12-1990","status":"close-to-expire"}},"fee":{"officialFee":[{"id":1,"official_fee_id":1,"amount":"4640.00","currency":"TRY","year":2023,"created_at":"2022-10-25T12:39:58.000000Z","updated_at":"2022-10-25T12:39:58.000000Z","name":"EP validation official fee"},{"id":25,"official_fee_id":25,"amount":"5040.00","currency":"TRY","year":2023,"created_at":null,"updated_at":null,"name":"Extension of 3 months official fees"}],"serviceFee":[{"id":6,"item":"EP validation additional time fee","slug":"ep-additional-time-fee","amount":"50","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"},{"id":1,"item":"EP validation service fee","slug":"ep-validation","amount":"185","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"}],"translation100keywordFee":"15","translationFee":{"fullTextCount":9293,"amount":"1,393.95","currency":"EUR"},"currency":{"eur":"20.0782"},"status":true}}');
    $isTest = true;
}
// Expired
if($number == "EPTST0004B1"){
    $data = json_decode('{"number":"EPTST0004B1","response":{"priorities":[{"documentNumber":"US19830479766","date":"1983\/03\/28"}],"bibliographicData":{"publicationReference":{"country":"EP","docNumber":"0123456","kind":"B1","date":"1990-12-12"},"inventionTitle":"A COMBINED INTRAFRAME AND INTERFRAME TRANSFORM CODING METHOD","applicant":"COMPRESSION LABS, INC","inventor":"CHEN, WEN-HSIUNG,","publicationDate":"12-12-1990","status":"expired"}},"fee":{"officialFee":[{"id":1,"official_fee_id":1,"amount":"4640.00","currency":"TRY","year":2023,"created_at":"2022-10-25T12:39:58.000000Z","updated_at":"2022-10-25T12:39:58.000000Z","name":"EP validation official fee"}],"translation100keywordFee":"15","translationFee":{"fullTextCount":9293,"amount":"1,393.95","currency":"EUR"},"serviceFee":[{"id":1,"item":"EP validation service fee","slug":"ep-validation","amount":"185","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"}],"currency":{"eur":"20.0782"},"status":true}}');
    $isTest = true;
}
// Very Close To Expire --URG
if($number == "EPTST0005B1URG"){
    $data = json_decode('{"number":"EPTST0005B1","response":{"bibliographicData":{"publicationReference":{"country":"EP","docNumber":"0123456","kind":"B1","date":"1990-12-12"},"inventionTitle":"A COMBINED INTRAFRAME AND INTERFRAME TRANSFORM CODING METHOD","applicant":"COMPRESSION LABS, INC","inventor":"CHEN, WEN-HSIUNG,","publicationDate":"12-12-1990","status":"close-to-expire"}},"fee":{"officialFee":[{"id":1,"official_fee_id":1,"amount":"4640.00","currency":"TRY","year":2023,"created_at":"2022-10-25T12:39:58.000000Z","updated_at":"2022-10-25T12:39:58.000000Z","name":"EP validation official fee"},{"id":25,"official_fee_id":25,"amount":"5040.00","currency":"TRY","year":2023,"created_at":null,"updated_at":null,"name":"Extension of 3 months official fees"}],"serviceFee":[{"id":6,"item":"EP validation additional time fee","slug":"ep-additional-time-fee","amount":"50","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"},{"id":1,"item":"EP validation service fee","slug":"ep-validation","amount":"185","currency":"EUR","created_at":null,"updated_at":null,"year":"2023"}],"translation100keywordFee":"15","translationFee":{"fullTextCount":9293,"amount":"1,393.95","currency":"EUR"},"currency":{"eur":"20.0782"},"status":true}}');
    $isTest = true;
}


if ($number == "WOTST0001A1") {
    $data = json_decode('
    {
        "number": "WOTST0001A1",
        "response": {
          "priorities": [
            {
              "documentNumber": "TSTDOCNUMBER0001A1",
              "date": "2020/07/15"
            }
          ],
          "bibliographicData": {
            "publicationReference": {
              "country": "WO",
              "docNumber": "2022012345",
              "kind": "A1",
              "date": "2022-01-20"
            },
            "inventionTitle": "TEST INVENTION TITLE",
            "applicant": "TEST APPLICANT",
            "inventor": "TEST INVENTOR",
            "publicationDate": "2022-01-20",
            "status": "expired",
            "applicationDate": "2021-07-01",
            "diff": 19
          }
        },
        "fee": {
          "additionalTimeFee": {
            "id": 4,
            "item": "Additional Time Fee",
            "slug": "additional-time-fee",
            "amount": "50",
            "currency": "EUR",
            "created_at": null,
            "updated_at": null,
            "year": "2023"
          },
          "officialFees": [
            {
              "id": 2,
              "code": "1.0.1.41",
              "item": "Patent İşbirliği Antlaşması Kapsamında Ulusal Aşamaya Giren Başvurunun Ücreti",
              "created_at": "2022-10-25T12:38:31.000000Z",
              "updated_at": "2022-10-25T12:38:50.000000Z",
              "amount": "3020.00",
              "year": 2023,
              "error": false
            },
            {
              "id": 4,
              "code": "1.0.1.23",
              "item": "3.Yıl Sicil Kayıt Ücreti",
              "created_at": "2022-10-25T12:38:31.000000Z",
              "updated_at": "2022-10-25T12:38:50.000000Z",
              "amount": "540.00",
              "year": 2023,
              "error": false
            },
            {
              "id": 22,
              "code": "1.0.1.02",
              "item": "Rüçhan Hakkı Talebi Ücreti (Her Bir Rüçhan İçin)",
              "created_at": "2022-10-25T20:52:31.000000Z",
              "updated_at": "2022-10-25T20:52:31.000000Z",
              "amount": "160.00",
              "year": 2023,
              "error": false
            },
            {
              "id": 23,
              "code": "1.0.1.51",
              "item": "İnceleme Raporu Düzenlenmesi Ücreti (1.01.50 Sayılı Satır Haricindeki Başvurular İçin)",
              "created_at": "2022-10-25T20:53:11.000000Z",
              "updated_at": "2022-10-25T20:53:11.000000Z",
              "amount": "1100.00",
              "year": 2023,
              "error": false
            },
            {
              "id": 24,
              "code": "1.0.1.42",
              "item": "Patent İşbirliği Antlaşması Kapsamında Ulusal Aşamaya Giren Başvurunun Yapılması İçin Ek Süre Ücreti",
              "created_at": null,
              "updated_at": null,
              "amount": "1530.00",
              "year": 2023,
              "error": false
            }
          ],
          "currency": {
            "eur": "20.1986"
          },
          "serviceFee": {
            "id": 3,
            "item": "PCT Entry",
            "slug": "pct-entry",
            "amount": "200",
            "currency": "EUR",
            "created_at": null,
            "updated_at": null,
            "year": "2023"
          },
          "translation100keywordFee": "15",
          "status": true,
          "translationFee": {
            "fullTextCount": 230,
            "amount": "34.50",
            "currency": "EUR"
          }
        }
      }');

    $isTest = true;
}


return $response = [
    "isTest" => $isTest ?? true,
    "data" => $data ?? null,
];
