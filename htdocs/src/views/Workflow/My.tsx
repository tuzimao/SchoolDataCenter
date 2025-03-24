import { useState, useEffect, Fragment } from 'react'
import axios from 'axios'
import { authConfig, defaultConfig } from 'src/configs/auth'
import { Grid, Box, Button, TextField } from '@mui/material'
import { DataGrid, GridColDef } from '@mui/x-data-grid'
import { useRouter } from 'next/router'
import StartModel from 'src/views/Workflow/Start'


const MyModel = () => {
  const [data, setData] = useState<any[]>([]);
  const [totalCount, setTotalCount] = useState(0);
  const [loading, setLoading] = useState(false);

  const [pageSize, setPageSize] = useState<number>(15)
  const [page, setPage] = useState<number>(0)
  const [search, setSearch] = useState<string>('')
  const [workType, setWorkType] = useState<string>('todo')
  const [buttonToDo, setButtonToDo] = useState<string>('contained')
  const [buttonDone, setButtonDone] = useState<string>('outlined')
  const [buttonAll, setButtonAll] = useState<string>('outlined')
  const [counter, setCounter] = useState<number>(0)
  const [pageModel, setPageModel] = useState('My')
  const [flowRecord, setFlowRecord] = useState<any>(null)
  
  const router = useRouter()

  const fetchData = async () => {
    setLoading(true);
    try {
      const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
      const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GetMyWorkList', { pageid: page, pageSize, search, workType, counter }, {
        headers: {
          Authorization: storedToken,
          'Content-Type': 'application/json'
        }
      });
      const data = response.data;
      setData(data.data);
      setTotalCount(data.totalCount);
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, [page, pageSize, search, counter, workType]);

  const handleReturnButton = () => {
    setPageModel('My')
    setFlowRecord(null)
    setCounter(counter+1)
  }

  const columns: GridColDef[] = [
    { field: 'id', headerName: '流水号', width: 100, headerAlign: 'center', align: 'center' },
    { field: '工作ID', headerName: '工作ID', width: 120, headerAlign: 'center', align: 'center' },
    { field: '工作名称', headerName: '工作名称', width: 400, headerAlign: 'center', align: 'center', renderCell: (params) => (
        <Button variant='text' onClick={() => {
            console.log("params", params.row)
            setFlowRecord(params.row)
            setPageModel('Start')
          }}>
          {params.value}
        </Button>
      ), },
    { field: '经办步骤', headerName: '我经办的步骤(流程图)', width: 300, headerAlign: 'center', align: 'center' },
    { field: '步骤状态', headerName: '步骤状态', width: 120, headerAlign: 'center', align: 'center' },
    { field: '发起人姓名', headerName: '发起人', width: 120, headerAlign: 'center', align: 'center' },
    { field: '工作接收时间', headerName: '到达时间', width: 200, headerAlign: 'center', align: 'center' }
  ];

  return (
    <Fragment>
      {pageModel == "My" && (
        <Box sx={{ width: '100%' }}>
          <Grid item xs={12} lg={12} sx={{ display: 'flex' }}>
            <Button sx={{ my: 3, mr: 5 }} size="small" variant='outlined' onClick={()=>{
              router.push('new')
            }}>新建工作</Button>
            <Button sx={{ my: 3, mr: 5 }} size="small" variant={buttonToDo as any} onClick={()=>{
              setButtonToDo('contained')
              setButtonDone('outlined')
              setButtonAll('outlined')
              setWorkType('todo')
              setSearch('')
            }}>待办工作</Button>
            <Button sx={{ my: 3, mr: 5 }} size="small" variant={buttonDone as any} onClick={()=>{
              setButtonToDo('outlined')
              setButtonDone('contained')
              setButtonAll('outlined')
              setWorkType('done')
              setSearch('')
            }}>办结工作</Button>
            <Button sx={{ my: 3, mr: 5 }} size="small" variant={buttonAll as any} onClick={()=>{
              setButtonToDo('outlined')
              setButtonDone('outlined')
              setButtonAll('contained')
              setWorkType('all')
              setSearch('')
            }}>全部工作</Button>
            <Button sx={{ my: 3, mr: 5 }} size="small" variant='outlined' onClick={()=>{
              setCounter(counter+1)
            }}>刷新</Button>
            <TextField
              value={search}
              label={'搜索'}
              onChange={(event) => setSearch(event.target.value)}
              sx={{mt: 3}}
              InputProps={{
                style: { height: '32px' }
              }}
              InputLabelProps={{
                shrink: true
              }}
            />
          </Grid>
          <DataGrid
              autoHeight
              rows={data}
              rowCount={totalCount as number}
              columns={columns}
              sortingMode='server'
              paginationMode='server'
              filterMode="server"
              loading={loading}
              rowsPerPageOptions={[10, 15, 20, 30]}
              page={page}
              pageSize={pageSize}
              disableColumnMenu={true}
              onPageChange={newPage => setPage(newPage)}
              onPageSizeChange={(newPageSize: number) => setPageSize(newPageSize)}
          />
        </Box>
      )}
      {pageModel == "Start" && flowRecord && (
        <StartModel FlowId={flowRecord.FlowId} handleReturnButton={handleReturnButton} flowRecord={flowRecord} />
      )}
    </Fragment>
  );
};

export default MyModel;
