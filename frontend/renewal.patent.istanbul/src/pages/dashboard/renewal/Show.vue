<template>
  <q-page class="page-container">
    <q-page-container style="padding-left: 0">
      <div class="q-pa-md">
        <div class="q-pa-md row items-start q-mt-xl">
          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">
                Renewal Details - {{ renewal.reference_no }}
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <div class="row">
                <div class="col">
                  <p class="text-h6 text-weight-medium">CC</p>
                  <p class="text-caption q-mt-0">
                    {{ renewal.cc }}
                  </p>
                  <!-- <p class="text-h3">{{ renewals.length }}</p> -->
                </div>
                <div class="col">
                  <p class="text-h6 text-weight-medium">Annuity/Status</p>
                  <p class="text-caption q-mt-0">
                    {{ renewal.annuity_status }}
                  </p>
                  <!-- <p class="text-h3">{{ renewals.length }}</p> -->
                </div>
                <div class="col">
                  <p class="text-h6 text-weight-medium">Application No</p>
                  <p class="text-caption q-mt-0">
                    {{ renewal.reference_no }}
                  </p>
                  <!-- <p class="text-h3">{{ renewals.length }}</p> -->
                </div>
                <div class="col">
                  <p class="text-h6 text-weight-medium">Region</p>
                  <p class="text-caption q-mt-0">
                    {{ renewal.region }}
                  </p>
                  <!-- <p class="text-h3">{{ renewals.length }}</p> -->
                </div>
                <div class="col">
                  <p class="text-h6 text-weight-medium">Filing Date</p>
                  <p class="text-caption q-mt-0">
                    {{ formatDate(renewal.filing_date) }}
                  </p>
                  <!-- <p class="text-h3">{{ renewals.length }}</p> -->
                </div>
                <div class="col">
                  <p class="text-h6 text-weight-medium">Renewal Date</p>
                  <p class="text-caption q-mt-0">
                    {{ renewal.renewal_date }}
                  </p>
                  <!-- <p class="text-h3">{{ renewals.length }}</p> -->
                </div>
                <div class="col">
                  <p class="text-h6 text-weight-medium">Status</p>
                  <p class="text-caption q-mt-0">
                    <q-badge
                      rounded
                      color="red"
                      label="Rejected"
                      v-if="renewal.status == 'rejected'"
                    />
                    <q-badge
                      rounded
                      color="green"
                      label="Approved"
                      v-if="renewal.status == 'approved'"
                    />
                    <q-badge
                      rounded
                      color="blue"
                      label="Pending"
                      v-if="renewal.status == 'pending'"
                    />
                  </p>
                  <!-- <p class="text-h3">{{ renewals.length }}</p> -->
                </div>
                <div class="col">
                  <p class="text-h6 text-weight-medium">Created At</p>
                  <p class="text-caption q-mt-0">2023-06-13</p>
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row items-start q-mt-sm">
          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">Fees</div>
              <p style="margin-bottom: 0; margin-top: 15px">
                Service fee, Agent fee and Official fee list
              </p>
            </q-card-section>
            <table class="table table-bordered q-pa-md">
              <thead>
                <tr>
                  <th class="q-pa-sm">
                    <span
                      ><span style="font-size: 18px">Fees</span><br /><span
                        style="font-weight: 400"
                        >Service and description</span
                      ></span
                    >
                  </th>
                  <th class="q-pa-sm">
                    <span style="font-weight: bold; font-size: 16"
                      ><span style="font-size: 18px">Price</span><br /><span
                        style="font-weight: 400"
                        >Service amount</span
                      ></span
                    >
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr style="background-color: #f0e9e9ba">
                  <td>
                    <strong>Official Fee (EUR)</strong><br /><span
                      >Amount to be paid to TURKPATENT (EUR Currency)</span
                    >
                  </td>
                  <td>{{ renewal.official_fee_eur }} EUR</td>
                </tr>
                <tr style="background-color: #f0e9e9ba">
                  <td>
                    <strong>Official Fee (ORIGINAL)</strong><br /><span
                      >Amount to be paid to TURKPATENT (TRY Currency)</span
                    >
                  </td>
                  <td>{{ renewal.official_fee_original }} TRY</td>
                </tr>
                <tr style="background-color: #e1e1edbd">
                  <td>
                    <strong>Agent Fee (EUR)</strong><br /><span
                      >Required fee for the agent (EUR Currency)</span
                    >
                  </td>
                  <td>{{ renewal.agent_fee_eur }} EUR</td>
                </tr>
                <tr style="background-color: #e1e1edbd">
                  <td>
                    <strong>Agent Fee (ORIGINAL)</strong><br /><span
                      >Required fee for the agent (TRY Currency)</span
                    >
                  </td>
                  <td>{{ renewal.agent_fee_original }} TRY</td>
                </tr>
                <tr style="background-color: #c7c7c46b">
                  <td>
                    <strong>Service Fee (EUR)</strong><br /><span
                      >Service Fee</span
                    >
                  </td>
                  <td>{{ renewal.service_fee_eur }} EUR</td>
                </tr>
                <tr style="background-color: #dfeaeca8">
                  <td>
                    <strong>Total (EUR)</strong><br /><span
                      >Total amount of all fees (IN EURO)</span
                    >
                  </td>
                  <td style="font-weight: 800">
                    {{ renewal.total_amount_eur }} EUR
                  </td>
                </tr>
              </tbody>
            </table>
          </q-card>
        </div>
        <div class="q-pa-md row items-start q-mt-sm">
          <q-card flat bordered class="monitor-card col-md-12 col-sm-12">
            <q-card-section>
              <div class="text-h5 text-weight-bold text-primary">Details</div>
            </q-card-section>

            <q-card-section class="q-pt-none" id="result">
              <div id="response">
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span>Payment Dates</span></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th><span>Order</span></th>
                          <th><span>Year</span></th>
                          <th><span>Payment Date</span></th>
                          <th>
                            <span><span>Amount Paid</span> </span
                            ><span>(₺)</span>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>3</td>
                          <td>2022</td>
                          <td>27-06-2022</td>
                          <td>602.33</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td>2023</td>
                          <td>16-05-2023</td>
                          <td>1210.0</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span>Application Details</span></span
                      >
                    </div>
                    <div class="actions"></div>
                  </div>
                  <div class="portlet-body form">
                    <div class="table table-bordered">
                      <div class="row">
                        <div class="col-md-4 col-xs-12 col-sm-4">
                          <div class="form-group">
                            <label
                              class="control-label col-md-12 col-xs-12 col-sm-12"
                              ><span
                                ><span><span>Application No</span> : </span
                                ><label>2020/08705</label></span
                              ></label
                            >
                          </div>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-4">
                          <div class="form-group">
                            <label
                              class="control-label col-md-12 col-xs-12 col-sm-12"
                              ><span
                                ><span><span>Application Date</span> : </span
                                ><label>04-06-2020</label></span
                              ></label
                            >
                          </div>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-4">
                          <div class="form-group">
                            <label
                              class="control-label col-md-12 col-xs-12 col-sm-12"
                              ><span
                                ><span><span>Application Form</span> : </span
                                ><label>Ulusal Başvuru</label></span
                              ></label
                            >
                          </div>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-4">
                          <div class="form-group">
                            <label
                              class="control-label col-md-12 col-xs-12 col-sm-12"
                              ><span
                                ><span><span>Document Number</span> : </span
                                ><label>2020-GE-235306</label></span
                              ></label
                            >
                          </div>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-4">
                          <div class="form-group">
                            <label
                              class="control-label col-md-12 col-xs-12 col-sm-12"
                              ><span
                                ><span><span>Document Date</span> : </span
                                ><label>04-06-2020</label></span
                              ></label
                            >
                          </div>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-4">
                          <div class="form-group">
                            <label
                              class="control-label col-md-12 col-xs-12 col-sm-12"
                              ><span
                                ><span><span>Protection Type</span> : </span
                                ><label>Patent</label></span
                              ></label
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span>Applicants</span></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th><span>Person No</span></th>
                          <th><span style="font-weight: bold">Name</span></th>
                          <th>
                            <span style="font-weight: bold">Address</span>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>6753911</td>
                          <td>SELÇUK TAŞ</td>
                          <td class="login-form"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span>Inventors</span></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th><span>Person No</span></th>
                          <th><span style="font-weight: bold">Name</span></th>
                          <th>
                            <span style="font-weight: bold">Address</span>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>6753911</td>
                          <td>SELÇUK TAŞ</td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span>Invention Information</span></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <table class="table table-bordered">
                      <tbody>
                        <tr>
                          <td>
                            <span style="font-weight: bold"
                              >Invention Name</span
                            >
                          </td>
                          <td>KAFES TİPİ GİDER KORUYUCU</td>
                        </tr>
                        <tr>
                          <td>
                            <span style="font-weight: bold"
                              >Invention Summary</span
                            >
                          </td>
                          <td colspan="2">
                            Buluş, gider borularının girişlerine takılarak gider
                            borularına katı atıkların girmesine engel olmak
                            üzere, kafes formunda ters U şekli verilebilen esnek
                            bir yapıya sahip bir gider koruyucu (100) ile
                            ilgilidir. Söz konusu gider koruyucu (100)
                            bahsedilen kafes formunu oluşturmak üzere yekpare
                            bükümlü bir tel eleman (110), bahsedilen tel
                            elemanın (110) yerleştirildiği, ortasında bir iç
                            boşluğa (121) ve bahsedilen iç boşluğu (121)
                            tanımlayan bir iç duvara (123) sahip bir dişi eleman
                            (120), tel elemanı (110) bahsedilen dişi elemana
                            (120) sabitlemek üzere dişi elemanın (120) üzerine
                            sıkı geçmeyle takılan bir erkek eleman (130)
                            içermektedir.
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span>Attorney Information</span></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <table class="table table-bordered">
                      <tbody>
                        <tr>
                          <td><span style="font-weight: bold">Name</span></td>
                          <td>
                            <span>CANAN ÖZTÜRKER</span><span><br /></span
                            ><span>PROUST PATENT DAN. LTD. ŞTİ.</span>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <span style="font-weight: bold">Address</span>
                          </td>
                          <td>***** İSTANBUL</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span>Priority Information</span></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th><span>Priority Date</span></th>
                          <th><span>Priority No</span></th>
                          <th><span>Country of Priority</span></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span
                          >Classification Classes of the Invention</span
                        ></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <div>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>
                              IPC:
                              <span style="font-weight: 400"
                                >F16L 55/24, E03C 1/26</span
                              >
                            </th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                    <div>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>
                              CPC:
                              <span style="font-weight: 400"
                                >E04D 2013/0413, E04D 13/0409, E04H 4/1236, Y10S
                                4/14, E04D 13/0767, E03F 5/0407, E03F
                                5/0404</span
                              >
                            </th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span
                          >Procedures Regarding Patent Application</span
                        ></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th><span>Date</span></th>
                          <th><span>Notification Date</span></th>
                          <th><span>Procedure</span></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>16-05-2023</td>
                          <td></td>
                          <td>Yıllık Ücret Ödeme Talebi</td>
                        </tr>
                        <tr>
                          <td>27-06-2022</td>
                          <td></td>
                          <td>Yıllık Ücret Ödeme Talebi</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td>
                            6769-P-1. İnceleme Bildirimine Karşı Cevap Alındı
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>08-06-2022</td>
                          <td></td>
                          <td>6769-P-1. İnceleme Bildirimine Karşı Cevap</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>11-03-2022</td>
                          <td>
                            6769-P-İnceleme Aşaması-1. Bildirim-Görüş Değişiklik
                            İstendi
                          </td>
                        </tr>
                        <tr>
                          <td>08-03-2022</td>
                          <td></td>
                          <td>1.İnceleme Raporu Geldi.</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td>
                            6769-Tarifname İstem Değişikliği Talebi Alındı
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td>
                            6769-P-Başvuru Sahibinin Araştırma Raporuna Karşı
                            Görüşü Alındı
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td>6769-P-İncelemeye Gönderildi</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>30-04-2021</td>
                          <td></td>
                          <td>6769-İnceleme Talebi-Ücret Var</td>
                        </tr>
                        <tr>
                          <td>30-04-2021</td>
                          <td></td>
                          <td>
                            6769-P-Başvuru Sahibinin Araştırma Raporuna Karşı
                            Görüşü
                          </td>
                        </tr>
                        <tr>
                          <td>30-04-2021</td>
                          <td></td>
                          <td>6769-Tarifname İstem Değişikliği Talebi</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>01-02-2021</td>
                          <td>
                            6769-P-Araştırma Raporu Gönderildi-İnceleme Talebi
                            İstendi
                          </td>
                        </tr>
                        <tr>
                          <td>27-01-2021</td>
                          <td></td>
                          <td>Araştırma Raporu Geldi.</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>10-01-2021</td>
                          <td>
                            6769-P-Şekli Eksiklik Giderildi-Araş. Talebi Var
                          </td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td>6769-P-Araştırmaya Gönderildi</td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>27-10-2020</td>
                          <td></td>
                          <td>6769-Şekli Eksiklik Giderme Talebi</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>20-10-2020</td>
                          <td>
                            6769-P-Tüm Belgeler Tamam-Şekli Eksiklik Bildirildi
                          </td>
                        </tr>
                        <tr>
                          <td>04-06-2020</td>
                          <td></td>
                          <td>6769-P-Araştırma talebi -Ücret Var</td>
                        </tr>
                        <tr>
                          <td>04-06-2020</td>
                          <td></td>
                          <td>
                            6769-Ulusal P/FM Başvurusu Geldi (Tüm Unsurlar Tam)
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="portlet light bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <span class="caption-subject"
                        ><span>Publications</span></span
                      >
                    </div>
                  </div>
                  <div class="portlet-body form">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th><span>Publication Date</span></th>
                          <th><span>Description</span></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>21-12-2021</td>
                          <td>
                            Araştırma Raporu İle Birlikte Yayımlanan Patent
                            Başvuruları (6769 SMK)
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </q-page-container>
  </q-page>
</template>

<script>
import { onMounted, ref } from "vue";
import { Loading } from "quasar";
import { useI18n } from "vue-i18n";
import { getRenewal, search } from "src/api/fetch";
import { useRouter } from "vue-router";
export default {
  name: "AppNumberMonitoring",
  setup() {
    const { t } = useI18n({ useScope: "global" });
    const renewal = ref([]);

    // Verilen tarihi dönüştürme fonksiyonu
    function formatDate(dateString) {
      var date = new Date(dateString);
      var year = date.getFullYear();
      var month = ("0" + (date.getMonth() + 1)).slice(-2);
      var day = ("0" + date.getDate()).slice(-2);
      return year + "-" + month + "-" + day;
    }

    onMounted(() => {
      Loading.show();
      const router = useRouter();
      getRenewal(router.currentRoute.value.params.id).then((res) => {
        renewal.value = res.renewal;
        Loading.hide();
      });

      search(renewal.value.reference_no).then((response) => {
        document.getElementById("result").style.display = "block";
        if (response) {

          if (response.status == false) {
            // write error message in #response div
            document.getElementById(
              "response"
            ).innerHTML = `<h4 class="text-center">${i18n.t(
              "no-results-found"
            )}: ${renewal.value.reference_no}</h4>`;
          } else {
            // console.log();
            // document.getElementById("result-title").innerHTML ='Search Details - ' + renewal.value.reference_no;
            // write all response in #response div
            // document.getElementById("response").innerHTML = response.data;
          }
        }
      });
    });

    return {
      t,
      renewal,
      patentNumber: ref(1),
      formatDate,
    };
  },
};
</script>

<style>
.caption {
  padding: 15px 0;
  font-weight: 600;
  font-size: 18px;
}
.caption::after {
  content: "";
  display: block;
  border-top: 1px solid black;
  margin-top: 10px;
}
label > span > span {
  font-weight: 600;
}
table {
  width: 100%;
}
table.table tr td span {
  font-weight: 400;
}
.table {
  width: 100% !important;
}
</style>
