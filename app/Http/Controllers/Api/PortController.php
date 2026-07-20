namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\Request;

class PortController extends Controller
{
    // Menampilkan halaman peta pelabuhan dunia
    public function index()
    {
        $ports = Port::all();
        return view('ports.index', compact('ports'));
    }

    // REST API Endpoint: GET /api/ports
    public function apiPorts(Request $request)
    {
        $query = Port::query();

        if ($request->has('country')) {
            $query->where('country_name', 'like', '%' . $request->country . '%');
        }

        if ($request->has('search')) {
            $query->where('port_name', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get()
        ]);
    }
}