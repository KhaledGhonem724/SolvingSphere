
<x-temp-general-layout :title="$title ?? 'Create Problem Page'">

<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <form method="POST" action="{{ route('problems.store') }}">
        @csrf

        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Enter URL:</label>
        <input
            type="url"
            id="problem-url"
            name="problem-url"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="https://example.com"
        >

        <button
            type="submit"
            class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition"
        >
            Submit
        </button>
    </form>
</div>

<br>
<hr>
<br>



<iframe src=https://www.hackerearth.com/submission/key/f74e5c4cbc7d4c609475736b8386d877/?theme=light width='100%' height='746px' frameborder='0' allowtransparency='true' scrolling='yes'></iframe>
<br>
<hr>
<br>



<p>
 The MEX of an array is the minimum non-negative integer that is not present in it. For example,
</p>
<ul>
 <li>
  The MEX of array
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-1">
     <span class="MJXp-mo" id="MJXp-Span-2" style="margin-left: 0em; margin-right: 0em;">
      [
     </span>
     <span class="MJXp-mn" id="MJXp-Span-3">
      2
     </span>
     <span class="MJXp-mo" id="MJXp-Span-4" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-5">
      5
     </span>
     <span class="MJXp-mo" id="MJXp-Span-6" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-7">
      0
     </span>
     <span class="MJXp-mo" id="MJXp-Span-8" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-9">
      1
     </span>
     <span class="MJXp-mo" id="MJXp-Span-10" style="margin-left: 0em; margin-right: 0em;">
      ]
     </span>
    </span>
   </span>
   <script id="MathJax-Element-1" type="math/tex">
    [2, 5,0,1]
   </script>
  </span>
  is
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-11">
     <span class="MJXp-mn" id="MJXp-Span-12">
      3
     </span>
    </span>
   </span>
   <script id="MathJax-Element-2" type="math/tex">
    3
   </script>
  </span>
  , because it contains
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-13">
     <span class="MJXp-mn" id="MJXp-Span-14">
      0
     </span>
     <span class="MJXp-mo" id="MJXp-Span-15" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-16">
      1
     </span>
     <span class="MJXp-mo" id="MJXp-Span-17" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-18">
      2
     </span>
    </span>
   </span>
   <script id="MathJax-Element-3" type="math/tex">
    0,1, 2
   </script>
  </span>
  and
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-19">
     <span class="MJXp-mn" id="MJXp-Span-20">
      5
     </span>
    </span>
   </span>
   <script id="MathJax-Element-4" type="math/tex">
    5
   </script>
  </span>
  but not
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-21">
     <span class="MJXp-mn" id="MJXp-Span-22">
      3
     </span>
    </span>
   </span>
   <script id="MathJax-Element-5" type="math/tex">
    3
   </script>
  </span>
  .
 </li>
 <li>
  The MEX of array
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-23">
     <span class="MJXp-mo" id="MJXp-Span-24" style="margin-left: 0em; margin-right: 0em;">
      [
     </span>
     <span class="MJXp-mn" id="MJXp-Span-25">
      1
     </span>
     <span class="MJXp-mo" id="MJXp-Span-26" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-27">
      2
     </span>
     <span class="MJXp-mo" id="MJXp-Span-28" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-29">
      5
     </span>
     <span class="MJXp-mo" id="MJXp-Span-30" style="margin-left: 0em; margin-right: 0em;">
      ]
     </span>
    </span>
   </span>
   <script id="MathJax-Element-6" type="math/tex">
    [1,2,5]
   </script>
  </span>
  is
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-31">
     <span class="MJXp-mn" id="MJXp-Span-32">
      0
     </span>
    </span>
   </span>
   <script id="MathJax-Element-7" type="math/tex">
    0
   </script>
  </span>
  , because
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-33">
     <span class="MJXp-mn" id="MJXp-Span-34">
      0
     </span>
    </span>
   </span>
   <script id="MathJax-Element-8" type="math/tex">
    0
   </script>
  </span>
  is the smallest non-negative integer not present in the array.
 </li>
 <li>
  The MEX of array
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-35">
     <span class="MJXp-mo" id="MJXp-Span-36" style="margin-left: 0em; margin-right: 0em;">
      [
     </span>
     <span class="MJXp-mn" id="MJXp-Span-37">
      0
     </span>
     <span class="MJXp-mo" id="MJXp-Span-38" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-39">
      1
     </span>
     <span class="MJXp-mo" id="MJXp-Span-40" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-41">
      2
     </span>
     <span class="MJXp-mo" id="MJXp-Span-42" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mn" id="MJXp-Span-43">
      3
     </span>
     <span class="MJXp-mo" id="MJXp-Span-44" style="margin-left: 0em; margin-right: 0em;">
      ]
     </span>
    </span>
   </span>
   <script id="MathJax-Element-9" type="math/tex">
    [0,1,2,3]
   </script>
  </span>
  is
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-45">
     <span class="MJXp-mn" id="MJXp-Span-46">
      4
     </span>
    </span>
   </span>
   <script id="MathJax-Element-10" type="math/tex">
    4
   </script>
  </span>
  .
 </li>
</ul>
<p>
 You are given a permutation
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-47">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-48">
     P
    </span>
   </span>
  </span>
  <script id="MathJax-Element-11" type="math/tex">
   P
  </script>
 </span>
 of the integers
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-49">
    <span class="MJXp-mo" id="MJXp-Span-50" style="margin-left: 0em; margin-right: 0em;">
     {
    </span>
    <span class="MJXp-mn" id="MJXp-Span-51">
     0
    </span>
    <span class="MJXp-mo" id="MJXp-Span-52" style="margin-left: 0em; margin-right: 0.222em;">
     ,
    </span>
    <span class="MJXp-mn" id="MJXp-Span-53">
     1
    </span>
    <span class="MJXp-mo" id="MJXp-Span-54" style="margin-left: 0em; margin-right: 0.222em;">
     ,
    </span>
    <span class="MJXp-mn" id="MJXp-Span-55">
     2
    </span>
    <span class="MJXp-mo" id="MJXp-Span-56" style="margin-left: 0em; margin-right: 0.222em;">
     ,
    </span>
    <span class="MJXp-mo" id="MJXp-Span-57" style="margin-left: 0em; margin-right: 0em;">
     …
    </span>
    <span class="MJXp-mo" id="MJXp-Span-58" style="margin-left: 0em; margin-right: 0.222em;">
     ,
    </span>
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-59">
     N
    </span>
    <span class="MJXp-mo" id="MJXp-Span-60" style="margin-left: 0.267em; margin-right: 0.267em;">
     −
    </span>
    <span class="MJXp-mn" id="MJXp-Span-61">
     1
    </span>
    <span class="MJXp-mo" id="MJXp-Span-62" style="margin-left: 0em; margin-right: 0em;">
     }
    </span>
   </span>
  </span>
  <script id="MathJax-Element-12" type="math/tex">
   \{0,1,2,\dots, N-1\}
  </script>
 </span>
 .
</p>
<p>
 For each
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-63">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-64">
     i
    </span>
   </span>
  </span>
  <script id="MathJax-Element-13" type="math/tex">
   i
  </script>
 </span>
 from
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-65">
    <span class="MJXp-mn" id="MJXp-Span-66">
     1
    </span>
   </span>
  </span>
  <script id="MathJax-Element-14" type="math/tex">
   1
  </script>
 </span>
 to
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-67">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-68">
     N
    </span>
   </span>
  </span>
  <script id="MathJax-Element-15" type="math/tex">
   N
  </script>
 </span>
 , find the number of subarrays of the permutation
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-69">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-70">
     P
    </span>
   </span>
  </span>
  <script id="MathJax-Element-16" type="math/tex">
   P
  </script>
 </span>
 such that the MEX of the subarray is equal to
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-71">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-72">
     i
    </span>
    <span class="MJXp-mo" id="MJXp-Span-73" style="margin-left: 0em; margin-right: 0.222em;">
     .
    </span>
   </span>
  </span>
  <script id="MathJax-Element-17" type="math/tex">
   i.
  </script>
 </span>
</p>
<p>
 An array
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-74">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-75">
     B
    </span>
   </span>
  </span>
  <script id="MathJax-Element-18" type="math/tex">
   B
  </script>
 </span>
 is a subarray of an array
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-76">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-77">
     A
    </span>
   </span>
  </span>
  <script id="MathJax-Element-19" type="math/tex">
   A
  </script>
 </span>
 if
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-78">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-79">
     B
    </span>
   </span>
  </span>
  <script id="MathJax-Element-20" type="math/tex">
   B
  </script>
 </span>
 can be obtained from aa by deletion of several (possibly, zero or all) elements from the beginning and several (possibly, zero or all) elements from the end. In particular, an array is a subarray of itself.
</p>
<p>
 <strong>
  Input format
 </strong>
</p>
<ul>
 <li>
  The first line of input contains an integer
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-80">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-81">
      T
     </span>
    </span>
   </span>
   <script id="MathJax-Element-21" type="math/tex">
    T
   </script>
  </span>
  denoting the number of test cases. The description of each test case is as follows:
 </li>
 <li>
  The first line of each test case contains an integer
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-82">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-83">
      N
     </span>
    </span>
   </span>
   <script id="MathJax-Element-22" type="math/tex">
    N
   </script>
  </span>
  denoting the length of the permutation
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-84">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-85">
      P
     </span>
    </span>
   </span>
   <script id="MathJax-Element-23" type="math/tex">
    P
   </script>
  </span>
  .
 </li>
 <li>
  The second line of each test case contains
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-86">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-87">
      N
     </span>
    </span>
   </span>
   <script id="MathJax-Element-24" type="math/tex">
    N
   </script>
  </span>
  integers
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-88">
     <span class="MJXp-msubsup" id="MJXp-Span-89">
      <span class="MJXp-mi MJXp-italic" id="MJXp-Span-90" style="margin-right: 0.05em;">
       P
      </span>
      <span class="MJXp-mn MJXp-script" id="MJXp-Span-91" style="vertical-align: -0.4em;">
       1
      </span>
     </span>
     <span class="MJXp-mo" id="MJXp-Span-92" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-msubsup" id="MJXp-Span-93">
      <span class="MJXp-mi MJXp-italic" id="MJXp-Span-94" style="margin-right: 0.05em;">
       P
      </span>
      <span class="MJXp-mn MJXp-script" id="MJXp-Span-95" style="vertical-align: -0.4em;">
       2
      </span>
     </span>
     <span class="MJXp-mo" id="MJXp-Span-96" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-mo" id="MJXp-Span-97" style="margin-left: 0em; margin-right: 0em;">
      …
     </span>
     <span class="MJXp-mo" id="MJXp-Span-98" style="margin-left: 0em; margin-right: 0.222em;">
      ,
     </span>
     <span class="MJXp-msubsup" id="MJXp-Span-99">
      <span class="MJXp-mi MJXp-italic" id="MJXp-Span-100" style="margin-right: 0.05em;">
       P
      </span>
      <span class="MJXp-mi MJXp-italic MJXp-script" id="MJXp-Span-101" style="vertical-align: -0.4em;">
       N
      </span>
     </span>
    </span>
   </span>
   <script id="MathJax-Element-25" type="math/tex">
    P_1, P_2,\dots, P_N
   </script>
  </span>
  , denoting the elements of the permutation
  <span class="mathjax-latex">
   <span class="MathJax_Preview" style="color: inherit;">
    <span class="MJXp-math" id="MJXp-Span-102">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-103">
      P
     </span>
    </span>
   </span>
   <script id="MathJax-Element-26" type="math/tex">
    P
   </script>
  </span>
  .
 </li>
</ul>
<p>
 <strong>
  Output format
 </strong>
</p>
<p>
 For each test case, print
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-104">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-105">
     N
    </span>
   </span>
  </span>
  <script id="MathJax-Element-27" type="math/tex">
   N
  </script>
 </span>
 space-separated integer where the
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-106">
    <span class="MJXp-msubsup" id="MJXp-Span-107">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-108" style="margin-right: 0.05em;">
      i
     </span>
     <span class="MJXp-mrow MJXp-script" id="MJXp-Span-109" style="vertical-align: 0.5em;">
      <span class="MJXp-mi MJXp-italic" id="MJXp-Span-110">
       t
      </span>
      <span class="MJXp-mi MJXp-italic" id="MJXp-Span-111">
       h
      </span>
     </span>
    </span>
   </span>
  </span>
  <script id="MathJax-Element-28" type="math/tex">
   i^{th}
  </script>
 </span>
 integer denotes the number of subarrays of the permutation
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-112">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-113">
     P
    </span>
   </span>
  </span>
  <script id="MathJax-Element-29" type="math/tex">
   P
  </script>
 </span>
 such that the MEX of the subarray is equal to
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-114">
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-115">
     i
    </span>
    <span class="MJXp-mo" id="MJXp-Span-116" style="margin-left: 0em; margin-right: 0.222em;">
     .
    </span>
   </span>
  </span>
  <script id="MathJax-Element-30" type="math/tex">
   i.
  </script>
 </span>
</p>
<p>
 <strong>
  Constraints
 </strong>
</p>
<p>
 <span class="mathjax-latex">
  <span class="MathJax_Preview" style="color: inherit;">
   <span class="MJXp-math" id="MJXp-Span-117">
    <span class="MJXp-mn" id="MJXp-Span-118">
     1
    </span>
    <span class="MJXp-mo" id="MJXp-Span-119" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≤
    </span>
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-120">
     T
    </span>
    <span class="MJXp-mo" id="MJXp-Span-121" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≤
    </span>
    <span class="MJXp-msubsup" id="MJXp-Span-122">
     <span class="MJXp-mn" id="MJXp-Span-123" style="margin-right: 0.05em;">
      10
     </span>
     <span class="MJXp-mn MJXp-script" id="MJXp-Span-124" style="vertical-align: 0.5em;">
      5
     </span>
    </span>
    <span class="MJXp-mspace" id="MJXp-Span-125" style="width: 0em; height: 0em;">
     <br/>
    </span>
    <span class="MJXp-mn" id="MJXp-Span-126">
     1
    </span>
    <span class="MJXp-mo" id="MJXp-Span-127" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≤
    </span>
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-128">
     N
    </span>
    <span class="MJXp-mo" id="MJXp-Span-129" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≤
    </span>
    <span class="MJXp-mn" id="MJXp-Span-130">
     3
    </span>
    <span class="MJXp-mo" id="MJXp-Span-131" style="margin-left: 0.267em; margin-right: 0.267em;">
     ⋅
    </span>
    <span class="MJXp-msubsup" id="MJXp-Span-132">
     <span class="MJXp-mn" id="MJXp-Span-133" style="margin-right: 0.05em;">
      10
     </span>
     <span class="MJXp-mn MJXp-script" id="MJXp-Span-134" style="vertical-align: 0.5em;">
      5
     </span>
    </span>
    <span class="MJXp-mspace" id="MJXp-Span-135" style="width: 0em; height: 0em;">
     <br/>
    </span>
    <span class="MJXp-mn" id="MJXp-Span-136">
     0
    </span>
    <span class="MJXp-mo" id="MJXp-Span-137" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≤
    </span>
    <span class="MJXp-msubsup" id="MJXp-Span-138">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-139" style="margin-right: 0.05em;">
      P
     </span>
     <span class="MJXp-mi MJXp-italic MJXp-script" id="MJXp-Span-140" style="vertical-align: -0.4em;">
      i
     </span>
    </span>
    <span class="MJXp-mo" id="MJXp-Span-141" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≤
    </span>
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-142">
     N
    </span>
    <span class="MJXp-mo" id="MJXp-Span-143" style="margin-left: 0.267em; margin-right: 0.267em;">
     −
    </span>
    <span class="MJXp-mn" id="MJXp-Span-144">
     1
    </span>
    <span class="MJXp-mo" id="MJXp-Span-145" style="margin-left: 0em; margin-right: 0.222em;">
     ,
    </span>
    <span class="MJXp-msubsup" id="MJXp-Span-146">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-147" style="margin-right: 0.05em;">
      P
     </span>
     <span class="MJXp-mi MJXp-italic MJXp-script" id="MJXp-Span-148" style="vertical-align: -0.4em;">
      i
     </span>
    </span>
    <span class="MJXp-mo" id="MJXp-Span-149" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≠
    </span>
    <span class="MJXp-msubsup" id="MJXp-Span-150">
     <span class="MJXp-mi MJXp-italic" id="MJXp-Span-151" style="margin-right: 0.05em;">
      P
     </span>
     <span class="MJXp-mi MJXp-italic MJXp-script" id="MJXp-Span-152" style="vertical-align: -0.4em;">
      j
     </span>
    </span>
    <span class="MJXp-mtext" id="MJXp-Span-153">
     for each
    </span>
    <span class="MJXp-mn" id="MJXp-Span-154">
     1
    </span>
    <span class="MJXp-mo" id="MJXp-Span-155" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≤
    </span>
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-156">
     i
    </span>
    <span class="MJXp-mo" id="MJXp-Span-157" style="margin-left: 0.333em; margin-right: 0.333em;">
     &lt;
    </span>
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-158">
     j
    </span>
    <span class="MJXp-mo" id="MJXp-Span-159" style="margin-left: 0.333em; margin-right: 0.333em;">
     ≤
    </span>
    <span class="MJXp-mi MJXp-italic" id="MJXp-Span-160">
     N
    </span>
    <span class="MJXp-mspace" id="MJXp-Span-161" style="width: 0em; height: 0em;">
     <br/>
    </span>
    <span class="MJXp-mrow" id="MJXp-Span-162">
     <span class="MJXp-mtext" id="MJXp-Span-163">
      Sum of
     </span>
     <span class="MJXp-mrow" id="MJXp-Span-164">
      <span class="MJXp-mi MJXp-italic" id="MJXp-Span-165">
       N
      </span>
     </span>
     <span class="MJXp-mtext" id="MJXp-Span-166">
      over all test cases does not exceed
     </span>
    </span>
    <span class="MJXp-mn" id="MJXp-Span-167">
     3
    </span>
    <span class="MJXp-mo" id="MJXp-Span-168" style="margin-left: 0.267em; margin-right: 0.267em;">
     ⋅
    </span>
    <span class="MJXp-msubsup" id="MJXp-Span-169">
     <span class="MJXp-mn" id="MJXp-Span-170" style="margin-right: 0.05em;">
      10
     </span>
     <span class="MJXp-mn MJXp-script" id="MJXp-Span-171" style="vertical-align: 0.5em;">
      5
     </span>
    </span>
    <span class="MJXp-mo" id="MJXp-Span-172" style="margin-left: 0em; margin-right: 0.222em;">
     .
    </span>
   </span>
  </span>
  <script id="MathJax-Element-31" type="math/tex">
   1\le T \le 10^5 \\ 1 \leq N \le 3\cdot 10^5\\ 0\le P_i \le N - 1, P_i \neq P_j \text{ for each } 1\le i \lt j \le N\\ \text{Sum of $N$ over all test cases does not exceed }3\cdot 10^5.
  </script>
 </span>
</p>
<p>
</p>

<div class="input-output right-border">
 <div class="form-label">
  <div class="weight-600 less-margin-right light float-left small">
   Sample Input
   <br/>
  </div>
  <div class="input-output-opt float-right">
   <a class="track-problem-sample-input tool-tip" href="https://he-s3-ap-south-1.s3.amazonaws.com/media/hackathon/january-circuits-23/problems/964f883098c011ed.txt?X-Amz-Algorithm=AWS4-HMAC-SHA256&amp;X-Amz-Credential=AKIA6I2ISGOYMPJGUFGY%2F20250521%2Fap-south-1%2Fs3%2Faws4_request&amp;X-Amz-Date=20250521T034559Z&amp;X-Amz-Expires=3600&amp;X-Amz-SignedHeaders=host&amp;X-Amz-Signature=3f4d296cf3bde88aeb3e009c2e0428e9fb90b532c3986c9e75c03999d290f404" rel="noopener noreferrer" target="_blank" title="Download">
    <i class="fa fa-link">
    </i>
   </a>
  </div>
  <div class="clear">
  </div>
  <br/>
 </div>
 <div class="dark">
  <pre class="word-spacing-0">2
3
2 0 1
5
4 0 2 1 3
</pre>
 </div>
 <br/>
</div>
<div class="input-output">
 <div class="form-label">
  <div class="weight-600 float-left less-margin-right light small">
   Sample Output
   <br/>
  </div>
  <div class="input-output-opt float-right">
   <a class="track-problem-sample-output tool-tip" href="https://he-s3-ap-south-1.s3.amazonaws.com/media/hackathon/january-circuits-23/problems/965bc26298c011ed.txt?X-Amz-Algorithm=AWS4-HMAC-SHA256&amp;X-Amz-Credential=AKIA6I2ISGOYMPJGUFGY%2F20250521%2Fap-south-1%2Fs3%2Faws4_request&amp;X-Amz-Date=20250521T034559Z&amp;X-Amz-Expires=3600&amp;X-Amz-SignedHeaders=host&amp;X-Amz-Signature=4e1626ad25ce708fd9e50e44bcf262b230a9f86d38be7b6e4f0fd7cc2bdfd08d" rel="noopener noreferrer" target="_blank" title="Download">
    <i class="fa fa-link">
    </i>
   </a>
  </div>
  <div class="clear">
  </div>
  <br/>
 </div>
 <div class="dark">
  <pre class="word-spacing-0">2 1 1
4 0 2 1 1
</pre>
 </div>
 <br/>
</div>
<div class="clear">
</div>

















<hr>
<hr>
<hr>
<hr>
<hr>
<hr>
















<div class="title">Explanation</div><div class="description"><p>In the first test case,&nbsp;</p>

<ul>
	<li>The subarrays with MEX =&nbsp;<span class="mathjax-latex"><span class="MathJax_Preview" style="color: inherit;"></span><span class="MathJax_SVG" id="MathJax-Element-32-Frame" tabindex="0" data-mathml="<math xmlns=&quot;http://www.w3.org/1998/Math/MathML&quot;><mn>1</mn></math>" role="presentation" style="font-size: 100%; display: inline-block; position: relative;"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="1.162ex" height="2.009ex" viewBox="0 -748.3 500.5 865.1" role="img" focusable="false" style="vertical-align: -0.271ex;" aria-hidden="true"><defs><path stroke-width="1" id="E32-MJMAIN-31" d="M213 578L200 573Q186 568 160 563T102 556H83V602H102Q149 604 189 617T245 641T273 663Q275 666 285 666Q294 666 302 660V361L303 61Q310 54 315 52T339 48T401 46H427V0H416Q395 3 257 3Q121 3 100 0H88V46H114Q136 46 152 46T177 47T193 50T201 52T207 57T213 61V578Z"></path></defs><g stroke="currentColor" fill="currentColor" stroke-width="0" transform="matrix(1 0 0 -1 0 0)"><use xlink:href="#E32-MJMAIN-31" x="0" y="0"></use></g></svg><span class="MJX_Assistive_MathML" role="presentation"><math xmlns="http://www.w3.org/1998/Math/MathML"><mn>1</mn></math></span></span><script type="math/tex" id="MathJax-Element-32">1</script></span>&nbsp;are :&nbsp;<span class="mathjax-latex"><span class="MathJax_Preview" style="color: inherit;"></span><span class="MathJax_SVG" id="MathJax-Element-33-Frame" tabindex="0" data-mathml="<math xmlns=&quot;http://www.w3.org/1998/Math/MathML&quot;><mo stretchy=&quot;false&quot;>[</mo><mn>2</mn><mo>,</mo><mn>0</mn><mo stretchy=&quot;false&quot;>]</mo><mo>,</mo><mo stretchy=&quot;false&quot;>[</mo><mn>0</mn><mo stretchy=&quot;false&quot;>]</mo><mo>.</mo></math>" role="presentation" style="font-size: 100%; display: inline-block; position: relative;"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="8.789ex" height="2.809ex" viewBox="0 -863.1 3784.3 1209.6" role="img" focusable="false" style="vertical-align: -0.805ex;" aria-hidden="true"><defs><path stroke-width="1" id="E33-MJMAIN-5B" d="M118 -250V750H255V710H158V-210H255V-250H118Z"></path><path stroke-width="1" id="E33-MJMAIN-32" d="M109 429Q82 429 66 447T50 491Q50 562 103 614T235 666Q326 666 387 610T449 465Q449 422 429 383T381 315T301 241Q265 210 201 149L142 93L218 92Q375 92 385 97Q392 99 409 186V189H449V186Q448 183 436 95T421 3V0H50V19V31Q50 38 56 46T86 81Q115 113 136 137Q145 147 170 174T204 211T233 244T261 278T284 308T305 340T320 369T333 401T340 431T343 464Q343 527 309 573T212 619Q179 619 154 602T119 569T109 550Q109 549 114 549Q132 549 151 535T170 489Q170 464 154 447T109 429Z"></path><path stroke-width="1" id="E33-MJMAIN-2C" d="M78 35T78 60T94 103T137 121Q165 121 187 96T210 8Q210 -27 201 -60T180 -117T154 -158T130 -185T117 -194Q113 -194 104 -185T95 -172Q95 -168 106 -156T131 -126T157 -76T173 -3V9L172 8Q170 7 167 6T161 3T152 1T140 0Q113 0 96 17Z"></path><path stroke-width="1" id="E33-MJMAIN-30" d="M96 585Q152 666 249 666Q297 666 345 640T423 548Q460 465 460 320Q460 165 417 83Q397 41 362 16T301 -15T250 -22Q224 -22 198 -16T137 16T82 83Q39 165 39 320Q39 494 96 585ZM321 597Q291 629 250 629Q208 629 178 597Q153 571 145 525T137 333Q137 175 145 125T181 46Q209 16 250 16Q290 16 318 46Q347 76 354 130T362 333Q362 478 354 524T321 597Z"></path><path stroke-width="1" id="E33-MJMAIN-5D" d="M22 710V750H159V-250H22V-210H119V710H22Z"></path><path stroke-width="1" id="E33-MJMAIN-2E" d="M78 60Q78 84 95 102T138 120Q162 120 180 104T199 61Q199 36 182 18T139 0T96 17T78 60Z"></path></defs><g stroke="currentColor" fill="currentColor" stroke-width="0" transform="matrix(1 0 0 -1 0 0)"><use xlink:href="#E33-MJMAIN-5B" x="0" y="0"></use><use xlink:href="#E33-MJMAIN-32" x="278" y="0"></use><use xlink:href="#E33-MJMAIN-2C" x="779" y="0"></use><use xlink:href="#E33-MJMAIN-30" x="1224" y="0"></use><use xlink:href="#E33-MJMAIN-5D" x="1724" y="0"></use><use xlink:href="#E33-MJMAIN-2C" x="2003" y="0"></use><use xlink:href="#E33-MJMAIN-5B" x="2448" y="0"></use><use xlink:href="#E33-MJMAIN-30" x="2726" y="0"></use><use xlink:href="#E33-MJMAIN-5D" x="3227" y="0"></use><use xlink:href="#E33-MJMAIN-2E" x="3505" y="0"></use></g></svg><span class="MJX_Assistive_MathML" role="presentation"><math xmlns="http://www.w3.org/1998/Math/MathML"><mo stretchy="false">[</mo><mn>2</mn><mo>,</mo><mn>0</mn><mo stretchy="false">]</mo><mo>,</mo><mo stretchy="false">[</mo><mn>0</mn><mo stretchy="false">]</mo><mo>.</mo></math></span></span><script type="math/tex" id="MathJax-Element-33">[2, 0],[0].</script></span></li>
	<li>
	<p>&nbsp;The subarray with MEX =&nbsp;<span class="mathjax-latex"><span class="MathJax_Preview" style="color: inherit;"></span><span class="MathJax_SVG" id="MathJax-Element-34-Frame" tabindex="0" data-mathml="<math xmlns=&quot;http://www.w3.org/1998/Math/MathML&quot;><mn>2</mn></math>" role="presentation" style="font-size: 100%; display: inline-block; position: relative;"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="1.162ex" height="2.009ex" viewBox="0 -748.3 500.5 865.1" role="img" focusable="false" style="vertical-align: -0.271ex;" aria-hidden="true"><defs><path stroke-width="1" id="E34-MJMAIN-32" d="M109 429Q82 429 66 447T50 491Q50 562 103 614T235 666Q326 666 387 610T449 465Q449 422 429 383T381 315T301 241Q265 210 201 149L142 93L218 92Q375 92 385 97Q392 99 409 186V189H449V186Q448 183 436 95T421 3V0H50V19V31Q50 38 56 46T86 81Q115 113 136 137Q145 147 170 174T204 211T233 244T261 278T284 308T305 340T320 369T333 401T340 431T343 464Q343 527 309 573T212 619Q179 619 154 602T119 569T109 550Q109 549 114 549Q132 549 151 535T170 489Q170 464 154 447T109 429Z"></path></defs><g stroke="currentColor" fill="currentColor" stroke-width="0" transform="matrix(1 0 0 -1 0 0)"><use xlink:href="#E34-MJMAIN-32" x="0" y="0"></use></g></svg><span class="MJX_Assistive_MathML" role="presentation"><math xmlns="http://www.w3.org/1998/Math/MathML"><mn>2</mn></math></span></span><script type="math/tex" id="MathJax-Element-34">2</script></span>&nbsp;is :&nbsp;<span class="mathjax-latex"><span class="MathJax_Preview" style="color: inherit;"></span><span class="MathJax_SVG" id="MathJax-Element-35-Frame" tabindex="0" data-mathml="<math xmlns=&quot;http://www.w3.org/1998/Math/MathML&quot;><mo stretchy=&quot;false&quot;>[</mo><mn>0</mn><mo>,</mo><mn>1</mn><mo stretchy=&quot;false&quot;>]</mo><mo>.</mo></math>" role="presentation" style="font-size: 100%; display: inline-block; position: relative;"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="5.299ex" height="2.809ex" viewBox="0 -863.1 2281.7 1209.6" role="img" focusable="false" style="vertical-align: -0.805ex;" aria-hidden="true"><defs><path stroke-width="1" id="E35-MJMAIN-5B" d="M118 -250V750H255V710H158V-210H255V-250H118Z"></path><path stroke-width="1" id="E35-MJMAIN-30" d="M96 585Q152 666 249 666Q297 666 345 640T423 548Q460 465 460 320Q460 165 417 83Q397 41 362 16T301 -15T250 -22Q224 -22 198 -16T137 16T82 83Q39 165 39 320Q39 494 96 585ZM321 597Q291 629 250 629Q208 629 178 597Q153 571 145 525T137 333Q137 175 145 125T181 46Q209 16 250 16Q290 16 318 46Q347 76 354 130T362 333Q362 478 354 524T321 597Z"></path><path stroke-width="1" id="E35-MJMAIN-2C" d="M78 35T78 60T94 103T137 121Q165 121 187 96T210 8Q210 -27 201 -60T180 -117T154 -158T130 -185T117 -194Q113 -194 104 -185T95 -172Q95 -168 106 -156T131 -126T157 -76T173 -3V9L172 8Q170 7 167 6T161 3T152 1T140 0Q113 0 96 17Z"></path><path stroke-width="1" id="E35-MJMAIN-31" d="M213 578L200 573Q186 568 160 563T102 556H83V602H102Q149 604 189 617T245 641T273 663Q275 666 285 666Q294 666 302 660V361L303 61Q310 54 315 52T339 48T401 46H427V0H416Q395 3 257 3Q121 3 100 0H88V46H114Q136 46 152 46T177 47T193 50T201 52T207 57T213 61V578Z"></path><path stroke-width="1" id="E35-MJMAIN-5D" d="M22 710V750H159V-250H22V-210H119V710H22Z"></path><path stroke-width="1" id="E35-MJMAIN-2E" d="M78 60Q78 84 95 102T138 120Q162 120 180 104T199 61Q199 36 182 18T139 0T96 17T78 60Z"></path></defs><g stroke="currentColor" fill="currentColor" stroke-width="0" transform="matrix(1 0 0 -1 0 0)"><use xlink:href="#E35-MJMAIN-5B" x="0" y="0"></use><use xlink:href="#E35-MJMAIN-30" x="278" y="0"></use><use xlink:href="#E35-MJMAIN-2C" x="779" y="0"></use><use xlink:href="#E35-MJMAIN-31" x="1224" y="0"></use><use xlink:href="#E35-MJMAIN-5D" x="1724" y="0"></use><use xlink:href="#E35-MJMAIN-2E" x="2003" y="0"></use></g></svg><span class="MJX_Assistive_MathML" role="presentation"><math xmlns="http://www.w3.org/1998/Math/MathML"><mo stretchy="false">[</mo><mn>0</mn><mo>,</mo><mn>1</mn><mo stretchy="false">]</mo><mo>.</mo></math></span></span><script type="math/tex" id="MathJax-Element-35">[0,1].</script></span>&nbsp;</p>
	</li>
	<li>
	<p>&nbsp;The subarray with MEX =&nbsp;<span class="mathjax-latex"><span class="MathJax_Preview" style="color: inherit;"></span><span class="MathJax_SVG" id="MathJax-Element-36-Frame" tabindex="0" data-mathml="<math xmlns=&quot;http://www.w3.org/1998/Math/MathML&quot;><mn>3</mn></math>" role="presentation" style="font-size: 100%; display: inline-block; position: relative;"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="1.162ex" height="2.009ex" viewBox="0 -748.3 500.5 865.1" role="img" focusable="false" style="vertical-align: -0.271ex;" aria-hidden="true"><defs><path stroke-width="1" id="E36-MJMAIN-33" d="M127 463Q100 463 85 480T69 524Q69 579 117 622T233 665Q268 665 277 664Q351 652 390 611T430 522Q430 470 396 421T302 350L299 348Q299 347 308 345T337 336T375 315Q457 262 457 175Q457 96 395 37T238 -22Q158 -22 100 21T42 130Q42 158 60 175T105 193Q133 193 151 175T169 130Q169 119 166 110T159 94T148 82T136 74T126 70T118 67L114 66Q165 21 238 21Q293 21 321 74Q338 107 338 175V195Q338 290 274 322Q259 328 213 329L171 330L168 332Q166 335 166 348Q166 366 174 366Q202 366 232 371Q266 376 294 413T322 525V533Q322 590 287 612Q265 626 240 626Q208 626 181 615T143 592T132 580H135Q138 579 143 578T153 573T165 566T175 555T183 540T186 520Q186 498 172 481T127 463Z"></path></defs><g stroke="currentColor" fill="currentColor" stroke-width="0" transform="matrix(1 0 0 -1 0 0)"><use xlink:href="#E36-MJMAIN-33" x="0" y="0"></use></g></svg><span class="MJX_Assistive_MathML" role="presentation"><math xmlns="http://www.w3.org/1998/Math/MathML"><mn>3</mn></math></span></span><script type="math/tex" id="MathJax-Element-36">3</script></span>&nbsp;is :&nbsp;<span class="mathjax-latex"><span class="MathJax_Preview" style="color: inherit;"></span><span class="MathJax_SVG" id="MathJax-Element-37-Frame" tabindex="0" data-mathml="<math xmlns=&quot;http://www.w3.org/1998/Math/MathML&quot;><mo stretchy=&quot;false&quot;>[</mo><mn>2</mn><mo>,</mo><mn>0</mn><mo>,</mo><mn>1</mn><mo stretchy=&quot;false&quot;>]</mo></math>" role="presentation" style="font-size: 100%; display: inline-block; position: relative;"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="6.849ex" height="2.809ex" viewBox="0 -863.1 2948.8 1209.6" role="img" focusable="false" style="vertical-align: -0.805ex;" aria-hidden="true"><defs><path stroke-width="1" id="E37-MJMAIN-5B" d="M118 -250V750H255V710H158V-210H255V-250H118Z"></path><path stroke-width="1" id="E37-MJMAIN-32" d="M109 429Q82 429 66 447T50 491Q50 562 103 614T235 666Q326 666 387 610T449 465Q449 422 429 383T381 315T301 241Q265 210 201 149L142 93L218 92Q375 92 385 97Q392 99 409 186V189H449V186Q448 183 436 95T421 3V0H50V19V31Q50 38 56 46T86 81Q115 113 136 137Q145 147 170 174T204 211T233 244T261 278T284 308T305 340T320 369T333 401T340 431T343 464Q343 527 309 573T212 619Q179 619 154 602T119 569T109 550Q109 549 114 549Q132 549 151 535T170 489Q170 464 154 447T109 429Z"></path><path stroke-width="1" id="E37-MJMAIN-2C" d="M78 35T78 60T94 103T137 121Q165 121 187 96T210 8Q210 -27 201 -60T180 -117T154 -158T130 -185T117 -194Q113 -194 104 -185T95 -172Q95 -168 106 -156T131 -126T157 -76T173 -3V9L172 8Q170 7 167 6T161 3T152 1T140 0Q113 0 96 17Z"></path><path stroke-width="1" id="E37-MJMAIN-30" d="M96 585Q152 666 249 666Q297 666 345 640T423 548Q460 465 460 320Q460 165 417 83Q397 41 362 16T301 -15T250 -22Q224 -22 198 -16T137 16T82 83Q39 165 39 320Q39 494 96 585ZM321 597Q291 629 250 629Q208 629 178 597Q153 571 145 525T137 333Q137 175 145 125T181 46Q209 16 250 16Q290 16 318 46Q347 76 354 130T362 333Q362 478 354 524T321 597Z"></path><path stroke-width="1" id="E37-MJMAIN-31" d="M213 578L200 573Q186 568 160 563T102 556H83V602H102Q149 604 189 617T245 641T273 663Q275 666 285 666Q294 666 302 660V361L303 61Q310 54 315 52T339 48T401 46H427V0H416Q395 3 257 3Q121 3 100 0H88V46H114Q136 46 152 46T177 47T193 50T201 52T207 57T213 61V578Z"></path><path stroke-width="1" id="E37-MJMAIN-5D" d="M22 710V750H159V-250H22V-210H119V710H22Z"></path></defs><g stroke="currentColor" fill="currentColor" stroke-width="0" transform="matrix(1 0 0 -1 0 0)"><use xlink:href="#E37-MJMAIN-5B" x="0" y="0"></use><use xlink:href="#E37-MJMAIN-32" x="278" y="0"></use><use xlink:href="#E37-MJMAIN-2C" x="779" y="0"></use><use xlink:href="#E37-MJMAIN-30" x="1224" y="0"></use><use xlink:href="#E37-MJMAIN-2C" x="1724" y="0"></use><use xlink:href="#E37-MJMAIN-31" x="2169" y="0"></use><use xlink:href="#E37-MJMAIN-5D" x="2670" y="0"></use></g></svg><span class="MJX_Assistive_MathML" role="presentation"><math xmlns="http://www.w3.org/1998/Math/MathML"><mo stretchy="false">[</mo><mn>2</mn><mo>,</mo><mn>0</mn><mo>,</mo><mn>1</mn><mo stretchy="false">]</mo></math></span></span><script type="math/tex" id="MathJax-Element-37">[2,0,1]</script></span>.</p>
	</li>
</ul>

<p>&nbsp;</p></div>

</x-temp-listing-layout>
