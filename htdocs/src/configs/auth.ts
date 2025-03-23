
export const AppSchoolConfigMap: any  = {}
AppSchoolConfigMap['localhost']       = ["http://localhost/api/", 'SchoolAI', "auth/menus.php", "http://localhost/api/"]
AppSchoolConfigMap['dandian.net']     = ["https://demoapi.dandian.net/api/", 'SchoolAI', "auth/menus.php", "https://demoapi.dandian.net/api/"]
AppSchoolConfigMap['fdzyzz.com']      = ["https://fdzz.dandian.net:8443/api/", '福鼎职中', "auth/menus.php", "https://fdzz.dandian.net:8443/api/"]
AppSchoolConfigMap['fjsmnx.com']      = ["https://dsj.fjsmlyxx.com:1443/api/", '三明林业', "auth/menus.php", "https://dsj.fjsmlyxx.com:1443/api/"]

const AppMarkId = "localhost"; //需要针对每个学校的情况进行修改

const APP_URL = AppSchoolConfigMap[AppMarkId][0]
const AppName = AppSchoolConfigMap[AppMarkId][1]
const indexMenuspath = AppSchoolConfigMap[AppMarkId][2]
const backEndApiAiBaseUrl = AppSchoolConfigMap[AppMarkId][3]

export const authConfig = {
    AppName: AppName,
    AppLogo: '/icons/' + AppMarkId + '/icon256.png',
    AppMarkId: AppMarkId,
    AppSchoolConfigMap: AppSchoolConfigMap,
    indexMenuspath: indexMenuspath,
    loginEndpoint: APP_URL + 'jwt.php?action=login',
    logoutEndpoint: APP_URL + 'jwt.php?action=logout',
    refreshEndpoint: APP_URL + 'jwt.php?action=refresh',
    registerEndpoint: APP_URL + 'jwt/register',
    backEndApiHost: APP_URL,
    backEndApiAiBaseUrl: backEndApiAiBaseUrl,
    indexImageUrl: '/images/school/' + AppMarkId + '/index.jpg',
    logoUrl: '/images/school/' + AppMarkId + '/logo.png'
}

export const defaultConfig = {
  Github: 'https://github.com/SmartSchoolAI/SchoolDataCenter',
  Docs: 'https://docs.dandian.net',
  AppVersion: '20250206',
  AppVersionType: '试用版本',
  defaultLanguage: 'zh',
  storageTokenKeyName: 'accessToken',
  storageAccessKeyName: 'accessKey',
  storageMainMenus: 'storageMainMenus',
  storageChatApp: 'storageChatApp',
  storageMyCoursesList: 'storageMyCoursesList',
}
