import { useState, useEffect, ChangeEvent } from 'react';
import axios from 'axios';
import { authConfig, defaultConfig } from 'src/configs/auth'
import CircularProgress from '@mui/material/CircularProgress'
import {
  Grid, 
  Box,
  Paper,
  Typography
} from '@mui/material';
import { DataGrid, GridColDef } from '@mui/x-data-grid';


interface WorkItem {
  serialNumber: string;
  workId: string;
  workName: string;
  startTime: string;
  isDeleted: boolean;
  isArchived: boolean;
  workLevel: number;
  [key: string]: string | boolean | number; // Index signature
}

const columns: GridColDef[] = [
  { field: 'id', headerName: '流水号', width: 120, headerAlign: 'center', align: 'center' },
  { field: '工作ID', headerName: '工作ID', width: 120, headerAlign: 'center', align: 'center' },
  { field: '工作名称', headerName: '工作名称', width: 200, headerAlign: 'center', align: 'center' },
  { field: '开始时间', headerName: '开始时间', width: 150, headerAlign: 'center', align: 'center' },
  { field: '删除标记', headerName: '删除标记', width: 100, headerAlign: 'center', align: 'center',
    valueFormatter: (params) => params.value ? '是' : '否' 
  },
  { field: '是否归档', headerName: '是否归档', width: 100, headerAlign: 'center', align: 'center',
    valueFormatter: (params) => params.value ? '是' : '否'
  },
  { field: '工作等级', headerName: '工作等级', width: 100, headerAlign: 'center', align: 'center' }
];

const MyModel = () => {
  const [data, setData] = useState<WorkItem[]>([]);
  const [paginationModel, setPaginationModel] = useState({
    page: 0,
    pageSize: 10,
  });
  const [totalCount, setTotalCount] = useState(0);
  const [loading, setLoading] = useState(false);

  const fetchData = async () => {
    setLoading(true);
    try {
      const storedToken = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
      const response = await axios.post(authConfig.backEndApiHost + 'workflow/start.php?action=GetMyWorkList', { pageid: paginationModel.page, pageSize: paginationModel.pageSize }, {
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
  }, [paginationModel]);

  return (
    <Box sx={{ height: 600, width: '100%' }}>
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
          page={paginationModel.page} // 使用 page 替代 paginationModel
          pageSize={paginationModel.pageSize} // 使用 pageSize 替代 paginationModel
          disableColumnMenu={true}
      />
    </Box>
  );
};

export default MyModel;
