import Report from 'src/views/Enginee/Report'

const ReportModel = () => {

  const backEndApi = 'data_report_demo.php'

  return (
    <Report backEndApi={backEndApi} />
  )
}

export default ReportModel
